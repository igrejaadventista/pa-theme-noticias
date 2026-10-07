import '../../node_modules/slim-js/dist/index';
import '../../node_modules/slim-js/dist/directives/all';
import './load-more';

const newsletterModals = document.querySelectorAll('[data-newsletter-modal]');

newsletterModals.forEach((modal) => {
  const trigger = document.querySelector(
    `[aria-controls="${modal.id}"][data-newsletter-modal-trigger]`,
  );
  const closeButton = modal.querySelector('[data-newsletter-modal-close]');
  const dialog = modal.querySelector('[data-newsletter-modal-dialog]');
  const content = modal.querySelector('[data-newsletter-modal-content]');
  const formTemplate = modal.querySelector('[data-newsletter-form-template]');
  let previouslyFocusedElement;

  const getFocusableElements = () => modal.querySelectorAll(
    'a[href], area[href], button:not([disabled]), iframe, input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])',
  );

  const closeModal = () => {
    content.replaceChildren();
    modal.hidden = true;
    document.body.classList.remove('pa-newsletter-modal-open');

    if (previouslyFocusedElement) {
      previouslyFocusedElement.focus();
    }
  };

  const getNewsletterForm = () => {
    const form = formTemplate.content.cloneNode(true);

    form.querySelectorAll('script').forEach((script) => {
      const executableScript = document.createElement('script');

      [...script.attributes].forEach((attribute) => {
        executableScript.setAttribute(attribute.name, attribute.value);
      });
      executableScript.textContent = script.textContent;
      script.replaceWith(executableScript);
    });

    return form;
  };

  const openModal = () => {
    previouslyFocusedElement = document.activeElement;

    content.append(getNewsletterForm());
    modal.hidden = false;
    document.body.classList.add('pa-newsletter-modal-open');

    // On touch devices, focusing the close button makes iOS Safari display its
    // native focus ring. Focus the dialog instead; the close button remains
    // the first element reached by keyboard navigation.
    if (window.matchMedia('(pointer: coarse)').matches && dialog) {
      dialog.focus();
      return;
    }

    closeButton.focus();
  };

  if (trigger) {
    trigger.addEventListener('click', openModal);
  }

  if (closeButton) {
    closeButton.addEventListener('click', closeModal);
  }

  modal.addEventListener('click', (event) => {
    if (event.target === modal) {
      closeModal();
    }
  });

  document.addEventListener('keydown', (event) => {
    if (modal.hidden) {
      return;
    }

    if (event.key === 'Escape') {
      closeModal();
      return;
    }

    if (event.key !== 'Tab') {
      return;
    }

    const focusableElements = getFocusableElements();
    const firstElement = focusableElements[0];
    const lastElement = focusableElements[focusableElements.length - 1];

    if (!firstElement || !lastElement) {
      event.preventDefault();
      return;
    }

    if (event.shiftKey && document.activeElement === firstElement) {
      event.preventDefault();
      lastElement.focus();
    } else if (!event.shiftKey && document.activeElement === lastElement) {
      event.preventDefault();
      firstElement.focus();
    }
  });
});
