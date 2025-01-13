<?php

namespace LaravelTolgeeTranslator\Classes;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use LaravelTolgeeTranslator\Utils\IO;
use Illuminate\Support\Facades\Config;
use LaravelTolgeeTranslator\Utils\Arr as ArrayUtils;

class Tolgee
{
    public static function sync_translations(){
        $translations = self::format_translations_array(self::get_all_translations());
        
        foreach($translations as $file_path => $data){
            IO::set_file_translations_array($file_path, $data);
        }
    }
    
    // Get all translations from tolgee
    private static function get_all_translations(){
        $translations = [];
        $page = 0;
        
        $languages = Arr::pluck(self::request('v2/projects/{project}/languages')['_embedded']['languages'], 'tag');
        
        do {
            $response = self::request('/v2/projects/{project}/translations', ['languages' => implode(',', $languages), 'size' => 1000, 'page' => $page]);
            $translations = array_merge($translations, $response['_embedded']['keys']);
            $page++;
        } while ($page < $response['page']['totalPages']);

        return $translations;
    }
    
    // Format translations from tolgee formato to associative array
    private static function format_translations_array($translations){
        $formatted_translations = [];
        
        foreach ($translations as $translation) {
            $key = $translation['keyName'];
            $namespace = $translation['keyNamespace'];

            foreach ($translation['translations'] as $locale => $translation) {
                if (
                    ($locale === Config::get('tolgee.locale') && !Config::get('tolgee.override')) ||
                    (!in_array($translation['state'], Config::get('tolgee.accepted_states')))
                ) {
                    continue;
                }

                $path_name = Str::replace('/'.Config::get('tolgee.locale'), '/' . $locale, $namespace);
                
                if(empty($formatted_translations[$path_name])){
                    $formatted_translations[$path_name] = IO::get_file_translations_array($path_name);
                }
                
                ArrayUtils::set_value_by_dot_notation($formatted_translations[$path_name], $key, $translation['text']);
            }
        }
        
        return $formatted_translations;
    }
    
    // Make tolgee request
    public static function request($endpoint, $data = null, $query = [], $method = 'get'){
        $response = Http::withHeaders(['Accept' => 'application/json', 'X-API-Key' => Config::get('tolgee.api_key')])
                    ->withUrlParameters(['project' => Config::get('tolgee.project_id')])
                    ->baseUrl(Config::get('tolgee.host'))
                    ->withQueryParameters($query)
                    ->$method($endpoint, $data);
        
        return $response->json();
    }
}
