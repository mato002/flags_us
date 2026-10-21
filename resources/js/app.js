import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

window.emTrackEvent = (eventName, payload = {}) => {
    // eslint-disable-next-line no-console
    console.log('[EffectiveMediaEvent]', eventName, payload);

    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push({
        event: eventName,
        ...payload,
    });

    if (typeof window.gtag === 'function') {
        window.gtag('event', eventName, payload);
    }

    if (typeof window.fbq === 'function' && ['quote_started', 'quote_submitted', 'whatsapp_clicked'].includes(eventName)) {
        window.fbq('trackCustom', eventName, payload);
    }
};

document.addEventListener('click', (event) => {
    const trackedEl = event.target.closest('[data-track-event]');
    if (trackedEl) {
        window.emTrackEvent(trackedEl.dataset.trackEvent, {
            href: trackedEl.getAttribute('href') || null,
            label: trackedEl.textContent?.trim() || null,
            location_hint: trackedEl.dataset.trackLocation || null,
        });
    }
});

document.addEventListener('DOMContentLoaded', () => {
    const modal = document.querySelector('[data-download-modal]');
    const openBtns = document.querySelectorAll('[data-open-download-modal], [data-download-trigger]');
    const closeBtns = document.querySelectorAll('[data-close-download-modal]');
    const filenameInput = document.querySelector('[data-download-filename]');

    if (!modal || !filenameInput) return;

    const openModal = (filename = '') => {
        filenameInput.value = filename;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    };

    const closeModal = () => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    };

    openBtns.forEach((button) => {
        button.addEventListener('click', (e) => {
            if (button.dataset.downloadTrigger !== undefined) {
                e.preventDefault();
                try {
                    const parsed = new URL(button.dataset.downloadUrl, window.location.origin);
                    const filename = parsed.pathname.split('/').pop() || '';
                    openModal(filename);
                } catch {
                    openModal('');
                }
            } else {
                openModal('EMF profile oct share.pdf');
            }
        });
    });

    closeBtns.forEach((button) => button.addEventListener('click', closeModal));
    modal.addEventListener('click', (e) => {
        if (e.target === modal) closeModal();
    });
});
