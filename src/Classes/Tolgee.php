<?php

namespace LaravelTolgeeTranslator\Classes;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Lang;
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
    
    public static function export_keys(){
        $base_lang_path = "lang";
        $translations = [];
        
        // Prepare local .php files
        $lang_path = $base_lang_path."/".Config::get('tolgee.locale');
        
        if (Config::get('tolgee.lang_subfolder')) {
            $lang_path .= '/'.Config::get('tolgee.lang_subfolder');
        }
        
        foreach (IO::get_all_files($lang_path) as $file) {
            $translations[$file] = Arr::dot(include $file);
        }
        
        if (!Config::get('tolgee.lang_subfolder')) {
            // Prepare json files translations
            foreach (IO::get_all_files($base_lang_path, 'json') as $file) {
                $locale = basename($file, '.json');
                
                if ($locale !== Config::get('tolgee.locale')) {
                    continue;
                }

                $translations[$file] = Arr::dot(Lang::getLoader()->load($locale, '*', '*'));
            }
        }
        
        // Remap everything into Tolgee request format
        $import = [];
        
        foreach ($translations as $namespace => $keys) {
            foreach ($keys as $key => $value) {
                if (is_array($value)) {
                    continue;
                }

                $import[] = ['name' => $key, 'namespace' => $namespace, 'translations' => [Config::get('tolgee.locale') => $value]];
            }
        }
        
        $response = self::request('/v2/projects/{project}/keys/import', ['keys' => $import], method: 'post');
    }
    
    public static function delete_keys(){
        $translations_ids = Arr::pluck(self::get_all_translations(), 'keyId');
        
        $response = self::request('/v2/projects/{project}/keys', ['ids' => $translations_ids], method: 'delete');
    }
    
    // Make tolgee request
    public static function request($endpoint, $data = null, $query = [], $method = 'get'){
        $response = Http::withHeaders(['Accept' => 'application/json', 'X-API-Key' => Config::get('tolgee.api_key')])
                    ->withUrlParameters(['project' => Config::get('tolgee.project_id')])
                    ->baseUrl(Config::get('tolgee.host'))
                    ->withQueryParameters($query)
                    ->$method($endpoint, $data);
        
        if ($response->failed()) {
            throw new \Exception($response->body(), 1);
        }
        
        return $response->json();
    }
}
