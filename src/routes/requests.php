<?php

use Illuminate\Support\Facades\Route;

Route::prefix('requests')->group(function () {
    Route::post('tolgee', function(){
        if(config("app.env") == "production" && !config("tolgee.sync_on_production")){
            return response("Can't sync on production", 403);
        }
        
        exec("cd .. && php artisan tolgee:sync-translations", $output, $code);
        
        dd($output, $code);
        
        return response(headers: ["HX-Refresh" => "true"]);
    })->name('tolgee.sync');
});