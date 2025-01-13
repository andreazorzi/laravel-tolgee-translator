<?php

namespace LaravelTolgeeTranslator;

use Spatie\LaravelPackageTools\Package;
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
            ]);
    }

}
