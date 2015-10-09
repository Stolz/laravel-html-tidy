<?php namespace Stolz\HtmlTidy;

class Middleware implements \Illuminate\Contracts\Routing\Middleware
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, \Closure $next)
	{
		return app('stolz.tidy')->handle($request, $next($request));
	}
}
