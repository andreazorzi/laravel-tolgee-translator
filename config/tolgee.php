<?php

return [
    /*
     * Tolgee service host
     */
    'host' => env('TOLGEE_HOST', 'https://app.tolgee.io'),
    
    /**
     * Your tolgee api key
     */
    'api_key' => env('TOLGEE_API_KEY'),
    
    /**
     * Tolgee project id
     */
    'project_id' => env('TOLGEE_PROJECT_ID'),
    
    /**
     * Default project language
     */
    'locale' => env('TOLGEE_LOCALE', 'en'),
    
    /**
     * Overwrite base locale translations files.
     */
    'override' => env('TOLGEE_OVERRIDE', false),
    
    /**
     * Accepted translation states. Check Tolgee documentation for available states.
     * Ex: REVIEWED,DISABLED,UNTRANSLATED,TRANSLATED
     */
    'accepted_states' => explode(",", env('TOLGEE_ACCEPTED_STATES', 'REVIEWED')),
    
    /**
     * Files to ignore during sync.
     */
    'ignore_files' => explode(",", env('TOLGEE_IGNORE_FILES', '')),
    
    /*
     * Set a specific subfolder for language files
     * Ex: app/lang/{locale}/tolgee -> "tolgee"
     */
    'lang_subfolder' => env('TOLGEE_LANG_SUBFOLDER'),
    
    /*
     * Set a specific subfolder for language files
     * Ex: app/lang/{locale}/tolgee -> "tolgee"
     */
    'sync_on_production' => env('TOLGEE_SYNC_ON_PRODUCTION', false),
];
