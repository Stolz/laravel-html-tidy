<?php namespace Stolz\HtmlTidy;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

class LegacyServiceProvider extends IlluminateServiceProvider
{
	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		// Register the package namespace
		//$this->package('stolz/tidy', 'tidy'); // Only valid if config file is at src/config/config.php
		$this->app->config->package('stolz/tidy', __DIR__, 'tidy');

		// Read settings from config file
		$config = $this->app->config->get('tidy::config', []);

		// Apply config settings
		$this->app['stolz.tidy']->config($config);
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		// Bind 'stolz.tidy' shared component to the IoC container
		$this->app->singleton('stolz.tidy', function ($app) {
			return new Tidy();
		});
	}
}
