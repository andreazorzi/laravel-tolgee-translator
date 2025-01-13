<?php

namespace LaravelTolgeeTranslator\Commands;

use Illuminate\Console\Command;
use LaravelTolgeeTranslator\Classes\Tolgee;

class SyncTranslations extends Command
{
    protected $signature = 'tolgee:sync-translations';

    protected $description = 'Sync the translations from Tolgee to your project';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        Tolgee::sync_translations();
    }
}
