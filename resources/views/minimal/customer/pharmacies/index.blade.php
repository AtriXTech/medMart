<x-layouts.customer title="My Pharmacies" active="pharmacies">
    <div class="card">
        <p class="section-title">Linked Pharmacies</p>
        <div id="pharmacy-list"></div>
    </div>

    <x-slot:scripts>
        <script>
            async function loadPharmacies() {
                try {
                    const data = await CustomerApi.get('/customer/pharmacies');
                    const pharmacies = data.data || data;
                    
                    const container = document.getElementById('pharmacy-list');
                    container.innerHTML = '';
                    
                    if (!pharmacies || pharmacies.length === 0) {
                        container.innerHTML = '<p class="empty-state">No pharmacies linked. Join one below.</p>';
                        return;
                    }
                    
                    pharmacies.forEach(function(pharmacy) {
                        const div = document.createElement('div');
                        div.style.cssText = 'padding: 12px; border: 1px solid var(--border); border-radius: var(--radius); margin-bottom: 8px;';
                        div.innerHTML = `
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <strong>${pharmacy.name}</strong>
                                    <div style="font-size: 12px; color: var(--text-muted);">Linked: ${new Date(pharmacy.linked_at).toLocaleDateString()}</div>
                                </div>
                                ${pharmacy.is_active 
                                    ? '<span class="badge badge-success">Active</span>'
                                    : `<button class="btn btn-secondary" onclick="switchPharmacy(${pharmacy.id})">Switch</button>`
                                }
                            </div>
                        `;
                        container.appendChild(div);
                    });
                } catch (error) {
                    console.error('Unable to load pharmacies:', error);
                }
            }
            
            window.switchPharmacy = async function(pharmacyId) {
                try {
                    await CustomerApi.patch('/customer/pharmacies/switch', { pharmacy_id: pharmacyId });
                    window.location.reload();
                } catch (error) {
                    alert(error.message || 'Unable to switch pharmacy.');
                }
            };
            
            loadPharmacies();
        </script>
    </x-slot:scripts>
</x-layouts.customer>