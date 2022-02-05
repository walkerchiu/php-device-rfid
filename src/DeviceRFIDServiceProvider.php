<?php

namespace WalkerChiu\DeviceRFID;

use Illuminate\Support\ServiceProvider;

class DeviceRFIDServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfig();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish config files
        $this->publishes([
           __DIR__ .'/config/device-rfid.php' => config_path('wk-device-rfid.php'),
        ], 'config');

        // Publish migration files
        $from = __DIR__ .'/database/migrations/';
        $to   = database_path('migrations') .'/';
        $this->publishes([
            $from .'create_wk_device_rfid_table.php'
                => $to .date('Y_m_d_His', time()) .'_create_wk_device_rfid_table.php',
        ], 'migrations');

        $this->loadTranslationsFrom(__DIR__.'/translations', 'php-device-rfid');
        $this->publishes([
            __DIR__.'/translations' => resource_path('lang/vendor/php-device-rfid'),
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                config('wk-device-rfid.command.cleaner')
            ]);
        }

        config('wk-core.class.device-rfid.reader')::observe(config('wk-core.class.device-rfid.readerObserver'));
        config('wk-core.class.device-rfid.readerLang')::observe(config('wk-core.class.device-rfid.readerLangObserver'));
        config('wk-core.class.device-rfid.readerRegister')::observe(config('wk-core.class.device-rfid.readerRegisterObserver'));
        config('wk-core.class.device-rfid.readerRegisterLang')::observe(config('wk-core.class.device-rfid.readerRegisterLangObserver'));
        config('wk-core.class.device-rfid.readerState')::observe(config('wk-core.class.device-rfid.readerStateObserver'));
        config('wk-core.class.device-rfid.readerStateLang')::observe(config('wk-core.class.device-rfid.readerStateLangObserver'));

        config('wk-core.class.device-rfid.card')::observe(config('wk-core.class.device-rfid.cardObserver'));
        config('wk-core.class.device-rfid.cardLang')::observe(config('wk-core.class.device-rfid.cardLangObserver'));

        config('wk-core.class.device-rfid.data')::observe(config('wk-core.class.device-rfid.dataObserver'));
    }

    /**
     * Merges user's and package's configs.
     *
     * @return void
     */
    private function mergeConfig()
    {
        if (!config()->has('wk-device-rfid')) {
            $this->mergeConfigFrom(
                __DIR__ .'/config/device-rfid.php', 'wk-device-rfid'
            );
        }

        $this->mergeConfigFrom(
            __DIR__ .'/config/device-rfid.php', 'device-rfid'
        );
    }

    /**
     * Merge the given configuration with the existing configuration.
     *
     * @param String  $path
     * @param String  $key
     * @return void
     */
    protected function mergeConfigFrom($path, $key)
    {
        if (
            !(
                $this->app instanceof CachesConfiguration
                && $this->app->configurationIsCached()
            )
        ) {
            $config = $this->app->make('config');
            $content = $config->get($key, []);

            $config->set($key, array_merge(
                require $path, $content
            ));
        }
    }
}
