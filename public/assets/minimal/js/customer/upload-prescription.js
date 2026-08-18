const uploadError = document.getElementById('upload-error');
const uploadSuccess = document.getElementById('upload-success');
const uploadForm = document.getElementById('upload-form');
const uploadSubmit = document.getElementById('upload-submit');
const fileInput = document.getElementById('file');

uploadForm.addEventListener('submit', async function(event) {
    event.preventDefault();
    uploadError.style.display = 'none';
    uploadSuccess.style.display = 'none';
    uploadSubmit.disabled = true;
    uploadSubmit.textContent = 'Uploading...';
    
    const file = fileInput.files[0];
    
    if (!file) {
        uploadError.textContent = 'Please select a file.';
        uploadError.style.display = 'block';
        uploadSubmit.disabled = false;
        uploadSubmit.textContent = 'Upload';
        return;
    }
    
    const formData = new FormData();
    formData.append('file', file);
    
    try {
        const token = CustomerApi.getToken();
        
        const response = await fetch('/api/v1/customer/prescriptions', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`,
            },
            body: formData,
        });
        
        const data = await response.json();
        
        if (!response.ok) {
            if (response.status === 422 && data.errors) {
                const messages = [];
                Object.keys(data.errors).forEach(function(key) {
                    messages.push(...data.errors[key]);
                });
                throw new Error(messages.join(', '));
            }
            throw new Error(data.message || 'Unable to upload prescription.');
        }
        
        uploadSuccess.textContent = 'Prescription uploaded successfully! It will be reviewed by the pharmacy.';
        uploadSuccess.style.display = 'block';
        uploadForm.reset();
        
        setTimeout(function() {
            window.location.href = '/customer/prescriptions';
        }, 2000);
    } catch (error) {
        uploadError.textContent = error.message || 'Unable to upload prescription.';
        uploadError.style.display = 'block';
    } finally {
        uploadSubmit.disabled = false;
        uploadSubmit.textContent = 'Upload';
    }
});