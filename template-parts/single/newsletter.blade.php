@php
    $newsletterSettingsContext = 'pa_settings';
    $newsletterEnabled = (bool) get_field('newsletter_banner_enabled', $newsletterSettingsContext);
    $newsletterImageId = (int) get_field('newsletter_banner_image', $newsletterSettingsContext);
    $newsletterAlt = trim((string) get_field('newsletter_banner_alt', $newsletterSettingsContext));
    $newsletterFormMarkup = trim((string) get_field('newsletter_form_code', $newsletterSettingsContext));
    $newsletterModalId = 'newsletter-modal-' . get_the_ID();

    if (empty($newsletterAlt)) {
        $newsletterAlt = __('Newsletter', 'iasd-noticias');
    }
@endphp

@if ($newsletterEnabled && $newsletterImageId && $newsletterFormMarkup)
    <section class="pa-newsletter-banner" aria-label="{{ esc_attr__('Newsletter', 'iasd-noticias') }}">
        <button
            class="pa-newsletter-banner__trigger"
            type="button"
            data-newsletter-modal-trigger
            aria-haspopup="dialog"
            aria-controls="{{ $newsletterModalId }}"
        >
            {!! wp_get_attachment_image($newsletterImageId, 'full', false, [
                'class' => 'pa-newsletter-banner__image',
                'alt' => $newsletterAlt,
            ]) !!}
        </button>
    </section>

    <div
        class="pa-newsletter-modal"
        id="{{ $newsletterModalId }}"
        data-newsletter-modal
        role="dialog"
        aria-modal="true"
        aria-labelledby="{{ $newsletterModalId }}-title"
        hidden
    >
        <div class="pa-newsletter-modal__dialog" data-newsletter-modal-dialog role="document" tabindex="-1">
            <h2 class="visually-hidden" id="{{ $newsletterModalId }}-title">
                {{ __('Newsletter', 'iasd-noticias') }}
            </h2>
            <button
                class="pa-newsletter-modal__close"
                type="button"
                data-newsletter-modal-close
                aria-label="{{ esc_attr__('Close newsletter form', 'iasd-noticias') }}"
            >
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="pa-newsletter-modal__content" data-newsletter-modal-content></div>
            <template data-newsletter-form-template>{!! $newsletterFormMarkup !!}</template>
        </div>
    </div>
@endif
