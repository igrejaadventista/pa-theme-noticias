<?php

use Extended\ACF\Fields\Image;
use Extended\ACF\Fields\Text;
use Extended\ACF\Fields\Textarea;
use Extended\ACF\Fields\TrueFalse;
use Extended\ACF\Location;

class PaAcfNewsletterFields
{
    public function __construct()
    {
        add_action('init', [$this, 'createACFFields']);
        add_action('admin_enqueue_scripts', [$this, 'enqueueMediaCompatibility']);
    }

    public function enqueueMediaCompatibility($hook)
    {
        if ($hook !== 'appearance_page_iasd_custom_settings') {
            return;
        }

        wp_enqueue_media();

        wp_add_inline_script(
            'media-views',
            "(function (wp) {
                if (!wp || !wp.media || !wp.media.view || !wp.media.view.settings) {
                    return;
                }

                wp.media.view.settings.defaultProps = wp.media.view.settings.defaultProps || {
                    align: 'none',
                    size: 'medium',
                    link: 'none'
                };
            })(window.wp);",
            'after'
        );
    }

    public function createACFFields()
    {
        register_extended_field_group([
            'title'  => __('Newsletter banner', 'iasd-noticias'),
            'key'    => 'newsletter_banner_settings',
            'style'  => 'default',
            'fields' => [
                TrueFalse::make(__('Enable newsletter banner', 'iasd-noticias'), 'newsletter_banner_enabled')
                    ->defaultValue(false)
                    ->stylisedUi(),
                Image::make(__('Banner image', 'iasd-noticias'), 'newsletter_banner_image')
                    ->returnFormat('id')
                    ->conditionalLogic([
                        \Extended\ACF\ConditionalLogic::where('newsletter_banner_enabled', '==', 1),
                    ]),
                Text::make(__('Banner image alternative text', 'iasd-noticias'), 'newsletter_banner_alt')
                    ->conditionalLogic([
                        \Extended\ACF\ConditionalLogic::where('newsletter_banner_enabled', '==', 1),
                    ]),
                Textarea::make(__('Newsletter form code', 'iasd-noticias'), 'newsletter_form_code')
                    ->instructions(__('Paste the complete form embed code, including any required styles and scripts.', 'iasd-noticias'))
                    ->rows(18)
                    ->conditionalLogic([
                        \Extended\ACF\ConditionalLogic::where('newsletter_banner_enabled', '==', 1),
                    ]),
            ],
            'location' => [
                Location::where('options_page', '==', 'iasd_custom_settings'),
            ],
        ]);
    }
}

$PaAcfNewsletterFields = new PaAcfNewsletterFields();
