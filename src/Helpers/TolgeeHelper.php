<?php

use Illuminate\Support\Str;
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
    function tolgee(string $key, array $replace = [], ?string $locale = null, bool $force_plain_text = false): string|array
    {
        if(!empty(config("tolgee.lang_subfolder"))){
            $key = config("tolgee.lang_subfolder").'/'.$key;
        }
        
        $translation = __($key, $replace, $locale);
        
        if((config('app.env') !== 'local' && !config('tolgee.sync_on_production')) || $force_plain_text || is_array($translation)){
            return $translation;
        }
        
        return '
            <span id="tolgee-'.Str::slug($key).'" title="Alt + Click to open this translation on Tolgee" onclick="if(event.altKey){ window.open(\''.Tolgee::get_translation_link($key).'\', \'_blank\'); return false; }">
                '.$translation.'
                
                <img src="https://docs.tolgee.io/img/tolgeeLogo.svg" />
            </span>
            <style>
                #tolgee-'.Str::slug($key).'{
                    color: inherit;
                    text-decoration: none;
                    display: inline-block;
                    padding: 3px 10px;
                    padding-right: 0px;
                    margin-top: -4px;
                    margin-left: -10px;
                    margin-right: -4px;
                    border: 1px solid transparent;
                    cursor: pointer;
                    
                    border-radius: 10px;
                    transition: all 0.3s;
                    -webkit-transition: all 0.3s;
                    -moz-transition: all 0.3s;
                    -o-transition: all 0.3s;
                    
                    --color: #ec407a;
                    
                    &:hover{
                        border: 1px solid var(--color);
                        background-color: color-mix(in srgb, var(--color), transparent 95%);
                        
                        & img{
                            display: inline-block !important;
                        }
                    }
                    
                    & img{
                        display: none;
                        width: 20px;
                    }
                }
            </style>
        ';
    }
}