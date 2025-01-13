<?php

namespace LaravelTolgeeTranslator\Utils;

use Illuminate\Support\Str;

class IO
{
    /**
     * Write a string to a file.
     */
    private static function write(string $content, string $path): void
    {
        $directory_path = dirname(base_path($path));
        
        if(!is_dir($directory_path)){
            mkdir($directory_path, recursive: true);
        }
        
        $file = fopen(base_path($path), 'w');
        fwrite($file, $content . PHP_EOL);
        fclose($file);
    }

    /**
     * Read json file and convert it into an array of strings.
     */
    private static function read(string $path): false|string
    {
        if (!file_exists($path)) {
            return false;
        }

        $file = fopen($path, 'r');
        $content = fread($file, filesize($path));
        fclose($file);
        
        return $content;
    }
    
    public static function get_file_translations_array($file_path){
        $data = [];
        
        if(!is_file(base_path($file_path))){
            return $data;
        }
        
        if(Str::contains($file_path, '.json')){
            $data = json_decode(self::read($file_path), true);
        }
        else{
            $data = include base_path($file_path);
        }
        
        return $data;
    }
    
    public static function set_file_translations_array($file_path, $data){
        $file_content = <<<'EOT'
                        <?php
                        
                        return {{translations}};
                        
                        EOT;
                        
        $file_content = Str::replace('{{translations}}', VarExport::pretty($data), $file_content);
        
        IO::write(Str::contains($file_path, '.json') ? json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $file_content, $file_path);
    }
}
