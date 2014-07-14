<?php namespace Stolz\Filters\HtmlTidy;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

class ServiceProvider extends IlluminateServiceProvider
{
	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		// Register the package namespace
		$this->package('stolz/laravel-html-tidy', 'html-tidy');

		// Read settings from config file
		$config = $this->app->config->get('html-tidy::config', array());

		// Apply config settings
		$this->app['stolz.filter.tidy']->config($config);
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		// Bind 'stolz.filter.tidy' shared component to the IoC container
		$this->app->singleton('stolz.filter.tidy', function ($app) {
			return new Filter();
		});
	}
}
