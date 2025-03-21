<?php

namespace LaravelTolgeeTranslator;

use Illuminate\Support\Facades\Blade;
use Spatie\LaravelPackageTools\Package;
use LaravelTolgeeTranslator\Commands\DeleteKeys;
use LaravelTolgeeTranslator\Commands\ExportKeys;
use LaravelTolgeeTranslator\Commands\SyncTranslations;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelTolgeeTranslatorServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-tolgee-translator')
            ->hasConfigFile('tolgee')
            ->hasCommands([
                SyncTranslations::class,
                ExportKeys::class,
                DeleteKeys::class,
            ])
            ->hasViews();
    }
    
    public function boot()
    {
        parent::boot();

        // Load package views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'tolgee');
        $this->loadRoutesFrom(__DIR__ . '/routes/requests.php');
        
        Blade::directive('tolgee', function ($key) {
            return "<?php echo tolgee($key); ?>";
        });
        
        Blade::directive('tolgeesync', function () {
            if(!empty(config("tolgee.project_id"))){
                return view('tolgee::components.sync-translations');
            }
        });
    }

}
