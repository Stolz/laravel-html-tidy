# Laravel HTML Tidy Filter

When editing HTML it's easy to make mistakes. Did you ever forget to close a `<div>` tag that made a mess of all your layout and then you went crazy trying to figure out what/where the problem was?. Wouldn't it be nice if there was a simple way to fix these mistakes automatically and tidy up sloppy editing into nicely layed out markup? Well that is what [HTML Tidy](http://www.w3.org/People/Raggett/tidy/) utility is for!. HTML Tidy is available as a [PHP extension](http://www.php.net/manual/en/book.tidy.php) and this package makes using it with Laravel a breeze.


[laravel-html-tidy](https://github.com/Stolz/laravel-html-tidy) is a [Laravel filter](http://laravel.com/docs/routing#route-filters) that parses Laravel's Response objects in order to detect and fix markup problems as well as to improve the layout and indent style of the resulting markup for you to debug the final HTML code of your views in a clean way




Installation
------------
To get the latest version of the filter simply require it in your composer.json file by running:

```bash
composer require stolz/laravel-html-tidy:dev-master --no-update
composer update stolz/laravel-html-tidy
```

Once the filter is installed you need to register the service provider with the application.
Open up `app/config/app.php` and find the `providers` key.

```php
'providers' => array(
    'Stolz\Filter\HtmlTidyServiceProvider',
)
```

Now add the filter to the bottom of your `app/filters.php` file:

```php
if (Config::get('html-tidy::bladeCacheExpiry') > 0) {
    Route::filter('html-tidy', 'Stolz\Filter\HtmlTidy');
}
```



Usage
-----

After following the instructions above, the filter is installed but not attached to any routes. To add caching to a
route, you need to add the `'cache'` filter both `'before'` and `'after'` the route in your `app/routes.php` file:

```php
Route::get('/url', array('before' => 'cache', 'after' => 'cache', 'uses' => 'Controller@method'));
```

To add caching to a collection of routes, use a route group:

```php
Route::group(array('before' => 'cache', 'after' => 'cache'), function() {
    Route::get('/url1', 'Controller@method1');
    Route::get('/url2', 'Controller@method2');
});
```


Configuration
-------------

To configure the package, you can use the following command to copy the configuration file to
`app/config/packages/stolz/laravel-html-tidy`.

```sh
php artisan config:publish stolz/laravel-html-tidy
```

Or you can just create a new file in that folder and only override the settings you need.

The settings themselves are documented inside `config.php`. The default configuration is for the filter to do nothing,
so you'll need to at least set the `'bladeCacheExpiry'` property to a positive number of minutes in the environments
where you want caching to be enabled.



License
-------

MIT License
(c) [Stolz](https://github.com/Stolz)
