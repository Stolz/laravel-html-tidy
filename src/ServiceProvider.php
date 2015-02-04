<?php namespace Stolz\HtmlTidy;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = true;

	/**
	 * Path to the default config file.
	 *
	 * @var string
	 */
	protected $configFile = __DIR__ . '/config.php';

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return ['stolz.tidy'];
	}

	/**
	 * Register bindings in the container.
	 *
	 * @return void
	 */
	public function register()
	{
		// Merge user's configuration
		$this->mergeConfigFrom($this->configFile, 'tidy');

		// Bind 'stolz.tidy' shared component to the IoC container
		$this->app->singleton('stolz.tidy', function ($app) {
			return new Middleware($app['config']['tidy']);
		});
	}

	/**
	 * Perform post-registration booting of services.
	 *
	 * @return void
	 */
	public function boot()
	{
		// Register paths to be published by 'vendor:publish' artisan command
		$this->publishes([
			$this->configFile => config_path('tidy.php'),
		]);
	}
}
