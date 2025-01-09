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
     * Override base locale translations files.
     */
    'override' => env('TOLGEE_OVERRIDE', false),
    
    /**
     * Accepted translation states. Check Tolgee documentation for available states.
     * Ex: REVIEWED,DISABLED,UNTRANSLATED,TRANSLATED
     */
    'accepted_states' => explode(",", env('TOLGEE_ACCEPTED_STATES', 'REVIEWED')),
    
    /*
     * Set a specific subfolder for language files
     * Ex: app/lang/{locale}/tolgee -> "tolgee"
     */
    'lang_subfolder' => env('TOLGEE_LANG_SUBFOLDER'),
];
