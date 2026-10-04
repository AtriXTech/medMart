/*
  New page — no previous version to diff against.

  - FAQ content (FAQS array below) is a starter draft — edit freely.
  - Chat panel: pharmacy NAME is real, fetched from the first entry of
    GET /customer/pharmacies. Email/phone are MOCK data — no
    customer-facing endpoint currently returns a pharmacy's email/phone
    (only the staff-only /staff/pharmacy-settings endpoint has them).
    See MOCK_PHARMACY_CONTACT below and the chat reply for the missing-
    endpoint note.
*/

const faqToggleBtn = document.getElementById('faq-toggle-btn');
const faqToggleIcon = document.getElementById('faq-toggle-icon');
const faqPanel = document.getElementById('faq-panel');

const policyToggleBtn = document.getElementById('policy-toggle-btn');
const policyToggleIcon = document.getElementById('policy-toggle-icon');
const policyPanel = document.getElementById('policy-panel');

const chatToggleBtn = document.getElementById('chat-toggle-btn');
const chatToggleIcon = document.getElementById('chat-toggle-icon');
const chatPanel = document.getElementById('chat-panel');
const chatLoading = document.getElementById('chat-loading');
const chatContent = document.getElementById('chat-content');



// function renderPolicies(){
   
// }
const privacyPolicy = window.LaravelRoutes.privacyPolicy; 
const termsAndCondition = window.LaravelRoutes.termsAndCondition; 
const refundAndCancellationPolicy = window.LaravelRoutes.refundAndCancellationPolicy; 
const cookiePolicy = window.LaravelRoutes.cookiePolicy; 
const disclaimer = window.LaravelRoutes.disclaimer; 

console.log(privacyPolicy); // This will now log the real URL string!

policyToggleBtn.addEventListener('click', function () {
    const isOpen = policyPanel.style.display === 'block';
    policyPanel.style.display = isOpen ? 'none' : 'block';
    
    const policies = [
        { name: 'Privacy Policy', url: privacyPolicy, icon: 'ph-shield-check' },
        { name: 'Terms & Conditions', url: termsAndCondition, icon: 'ph-file-text' },
        { name: 'Refund Policy', url: refundAndCancellationPolicy, icon: 'ph-receipt' },
        { name: 'Cookie Policy', url: cookiePolicy, icon: 'ph-cookie' },
        { name: 'Disclaimer', url: disclaimer, icon: 'ph-warning-circle' }
    ];

  policyPanel.innerHTML = `
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
        ${policies.map(policy => `
            <a href="${policy.url}"
               class="flex items-center justify-center gap-2 w-full py-3 rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white font-inter font-semibold text-[14px] shadow-md shadow-[#2775E4]/20 mb-2">
                <i class="ph-fill ${policy.icon} text-[18px]"></i>
                ${policy.name}
            </a>
        `).join('')}
    </div>
`;
    
    policyToggleIcon.style.transform = isOpen ? '' : 'rotate(90deg)';
});/* ==================== FAQ — starter draft, edit freely ==================== */

const FAQS = [
    { q: 'How do I place an order?', a: 'Browse products from your linked pharmacy, add items to your cart, and tap Checkout. Choose pickup or delivery before confirming your order.' },
    { q: 'How do I pay for my order?', a: 'Payments are made securely online at checkout. Once payment is confirmed, your order status will update automatically.' },
    { q: 'Do I need a prescription for every medication?', a: 'No — only medications marked "Rx Required" need a prescription. You can upload it during checkout or from the Prescriptions section.' },
    { q: 'How long does delivery take?', a: 'Delivery times vary by pharmacy and location. You can track your order status at any time from the Orders page.' },
    { q: 'Can I pick up my order instead of getting it delivered?', a: 'Yes — choose "Pickup" as your fulfillment option at checkout, and you\'ll be notified once your order is ready.' },
    { q: 'How do I track my order?', a: 'Open the Orders page to see the current status of every order, from processing to delivery or pickup.' },
    { q: 'Can I cancel an order after placing it?', a: 'You can cancel an order from its details page, as long as it hasn\'t already been processed or dispatched.' },
    { q: 'How do I switch to a different pharmacy?', a: 'Go to Pharmacies from your account menu to join a new pharmacy with its code, or switch between pharmacies you\'re already linked to.' },
];

function renderFaqs() {
    faqPanel.innerHTML = FAQS.map(function (item, index) {
        return `<div class=" border-t border-[#F3F7FC] first:border-t-0 py-3">
            <button type="button" class="faq-item-toggle w-full flex items-center justify-between gap-3 text-left" data-index="${index}">
                <span class="font-inter text-[14px] font-semibold text-[#171E26]">${item.q}</span>
                <i class="ph-light ph-plus text-[16px] text-[#171E26]/40 flex-shrink-0 faq-item-icon"></i>
            </button>
            <p class="faq-item-answer font-inter text-[13.5px] text-[#171E26]/60 leading-relaxed mt-2" style="display: none;">${item.a}</p>
        </div>`;
    }).join('');

    faqPanel.querySelectorAll('.faq-item-toggle').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const answer = btn.parentElement.querySelector('.faq-item-answer');
            const icon = btn.querySelector('.faq-item-icon');
            const isOpen = answer.style.display === 'block';
            answer.style.display = isOpen ? 'none' : 'block';
            icon.className = isOpen
                ? 'ph-light ph-plus text-[16px] text-[#171E26]/40 flex-shrink-0 faq-item-icon'
                : 'ph-light ph-minus text-[16px] text-[#171E26]/40 flex-shrink-0 faq-item-icon';
        });
    });
}

faqToggleBtn.addEventListener('click', function () {
    const isOpen = faqPanel.style.display === 'block';
    faqPanel.style.display = isOpen ? 'none' : 'block';
    faqToggleIcon.style.transform = isOpen ? '' : 'rotate(90deg)';
});

/* ==================== Chat / pharmacy contact ==================== */

// MOCK — no customer-facing endpoint returns a pharmacy's email/phone
// today. Replace once that endpoint exists.
const MOCK_PHARMACY_CONTACT = {
    email: 'support@yourpharmacy.com',
    phone: '+2348012345678',
};

function whatsappLink(phone) {
    const digits = String(phone || '').replace(/[^\d]/g, '');
    return `https://wa.me/${digits}`;
}

async function loadPharmacyContact() {
    chatLoading.style.display = 'block';
    chatContent.style.display = 'none';

    // Real: pharmacy name from the first linked pharmacy. Defaulting to
    // the first entry since there's no confirmed way to know which one
    // is "currently active" from the client — see chat reply.
    let pharmacyName = 'Your Pharmacy';
  try {
    const response = await CustomerApi.get('/customer/pharmacies');
    const pharmacies = response.data || response;

    if (pharmacies && pharmacies.length > 0) {
        const activePharmacy = pharmacies.find(
            pharmacy => pharmacy.is_active === true
        );

        if (activePharmacy) {
            pharmacyName = activePharmacy.name;
            pharmacyPhone = activePharmacy.phone;
            pharmacyEmail = activePharmacy.email;
        }
    }
} catch (error) {
    console.error('Unable to load pharmacy:', error);
}

    chatContent.innerHTML = `
        <div class="rounded-xl bg-[#F7FAFD] border border-[#EAF1FB] p-4 mb-4">
            <p class="font-manrope font-bold text-[15px] text-[#171E26]">${pharmacyName}</p>
            <p class="font-inter text-[13px] text-[#171E26]/55 mt-1">${pharmacyEmail}</p>
            <p class="font-inter text-[13px] text-[#171E26]/55">${pharmacyPhone}</p>
        </div>
        <a href="${whatsappLink(pharmacyPhone)}" target="_blank" rel="noopener"
           class="flex items-center justify-center gap-2 w-full py-3 rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white font-inter font-semibold text-[14px] shadow-md shadow-[#2775E4]/20">
            <i class="ph-fill ph-whatsapp-logo text-[18px]"></i> Chat
        </a>
    `;

    chatLoading.style.display = 'none';
    chatContent.style.display = 'block';
}

let chatLoaded = false;
chatToggleBtn.addEventListener('click', function () {
    const isOpen = chatPanel.style.display === 'block';
    chatPanel.style.display = isOpen ? 'none' : 'block';
    chatToggleIcon.style.transform = isOpen ? '' : 'rotate(90deg)';

    if (!isOpen && !chatLoaded) {
        chatLoaded = true;
        loadPharmacyContact();
    }
});

renderFaqs();