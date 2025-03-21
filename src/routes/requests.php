<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::prefix('requests')->group(function () {
    Route::post('tolgee', function(){
        if(config("app.env") == "production" && !config("tolgee.sync_on_production")){
            return response("Can't sync tolgee translations on production", 403);
        }
        
        Artisan::call('tolgee:sync-translations');
        
        return response(headers: ["HX-Refresh" => "true"]);
    })->name('tolgee.sync');
});