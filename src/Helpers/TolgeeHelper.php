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
    function tolgee(string $key, array $replace = [], ?string $locale = null): string
    {
        $is_local = config('app.env') === 'local';
        
        if(!empty(config("tolgee.lang_subfolder"))){
            $key = config("tolgee.lang_subfolder").'/'.$key;
        }
        
        return '
            <a id="tolgee-'.Str::slug($key).'" href="'.Tolgee::get_translation_link($key).'" target="_blank" class="tolgee-link '.($is_local ? 'tolgee-link-local' : '').'">
                <span title="'.$key.'">
                    '.__($key, $replace, $locale).'
                    
                    <img src="https://docs.tolgee.io/img/tolgeeLogo.svg" />
                </span>
            </a>
            <style>
                #tolgee-'.Str::slug($key).'{
                    color: inherit;
                    text-decoration: none;
                
                    & span{
                        display: inline-block;
                        padding: 3px 10px;
                        margin-top: -4px;
                        margin-left: -10px;
                        border: 1px solid transparent;
                        
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
                }
            </style>
        ';
    }
}