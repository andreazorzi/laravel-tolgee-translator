<?php

namespace LaravelTolgeeTranslator\Commands;

use Illuminate\Console\Command;
use LaravelTolgeeTranslator\Classes\Tolgee;

class ExportKeys extends Command
{
    protected $signature = 'tolgee:export-keys';

    protected $description = 'Export all project translations keys and save them into tolgee project, existing keys will not be overwritten.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        Tolgee::export_keys();
        
        $this->info('Exported keys successfully!');
    }
}
