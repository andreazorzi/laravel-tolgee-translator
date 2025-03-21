<?php

namespace LaravelTolgeeTranslator\Commands;

use Illuminate\Console\Command;
use LaravelTolgeeTranslator\Classes\Tolgee;

class DeleteKeys extends Command
{
    protected $signature = 'tolgee:delete-keys';

    protected $description = 'Delete all keys from the tolgee project.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $confirm = $this->confirm('Are you sure you want to delete all keys from tolgee? This action cannot be undone.');
        
        if($confirm){
            Tolgee::delete_keys();
        }
        
        $this->info($confirm ? 'Keys deleted successfully!' : 'Operation cancelled.');
    }
}
