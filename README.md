# Laravel HTML Tidy

## tl;dr
[Laravel-html-tidy](https://github.com/Stolz/laravel-html-tidy) is a [Laravel middleware](http://laravel.com/docs/master/middleware) that parses Laravel's *Response* objects in order to detect and fix markup problems as well as to improve the layout and indent style of the resulting markup.

## How it works

When editing HTML it's easy to make mistakes. Did you ever forget to close a `<div>` tag that made a mess of all your layout and then you went crazy trying to figure out what/where the problem was?. Wouldn't it be nice if there was a simple way to detect and fix these mistakes automatically and at the same time tidy up sloppy editing into nicely layed out markup? Well that is what [W3C HTML Tidy](http://www.w3.org/People/Raggett/tidy/) utility is for!. HTML Tidy is available as an official [PHP extension](http://www.php.net/manual/en/book.tidy.php) and this package makes using it with Laravel a breeze.

Once the middleware is enabled every time there is a problem with your HTML code you will see an error messages on your screen. Tidy will try its best to fix the problem for you. Also, if you take a look of the final HTML code sent to the browser you will get a pleasant surprise.

**Note:** HTML Tidy is fast but parsing output always adds a small overhead so consider using the middleware only in development environments.

## Requirements

- PHP compiled with HTML Tidy support and [Tidy](http://php.net/manual/en/book.tidy.php) extension enabled in php.ini.
- Laravel framework.

## Installation

Install via composer

	composer require stolz/laravel-html-tidy

Then edit `config/app.php` and add the service provider within the `providers` array:

	'providers' => [
		//...
		'Stolz\HtmlTidy\ServiceProvider',
	],

The default settings can validate both (x)HTML 4 and HTML 5 markups. If you want to customize the settings create the file `config/tidy.php` by running

	php artisan vendor:publish

## Usage

If you want the middleware to be run only on specific routes, add the class in the `$routeMiddleware` property of your `app/Http/Kernel.php` file, with your desired short-hand key.

	protected $routeMiddleware = [
		...
		'tidy' => 'Stolz\HtmlTidy\Middleware',
	];

Now in your `routes.php` file you can use

	get('some/url', [
		'middleware' => 'tidy',
		function() {
			return view('hello');
		}
	]);

Conversely if you want the middleware to be run on every HTTP request to your application, add the class in the `$middleware` property of your `app/Http/Kernel.php` file.

	protected $middleware = [
		...
		'Stolz\HtmlTidy\Middleware',
	];

## License

MIT License
© [Stolz](https://github.com/Stolz)
