<?php

use LaravelTolgeeTranslator\Classes\Tolgee;

if (!function_exists('tolgee')) {
    /**
     * Helper to insert translations.
     *
     * @param string $key
     * @param array $replace
     * @param string|null $locale
     * @return string
     */
    function tolgee(string $key, array $replace = [], string $locale = null): string
    {
        $is_local = config('app.env') === 'local';
        
        return '
            <span title="'.$key.'">
                '.__($key, $replace, $locale).'
                
                <a href="'.Tolgee::get_translation_link($key).'" target="_blank" class="tolgee-link '.($is_local ? 'tolgee-link-local' : '').'">
                    <img src="https://docs.tolgee.io/img/tolgeeLogo.svg" style="width: 20px;" />
                </a>
            </span>
        ';
    }
}