<?php namespace Stolz\HtmlTidy;

use Illuminate\Contracts\Routing\Middleware as BaseMiddleware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class Middleware implements BaseMiddleware
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
		$response = $next($request);

		// Check PHP extension
		if( ! extension_loaded('tidy') or ! config('tidy.enabled'))
			return $response;

		// Check request
		if($request->ajax() and ! config('tidy.ajax'))
			return $response;

		// Skip redirects
		if($response instanceof RedirectResponse)
			return $response;

		// Convert unknown responses
		if( ! $response instanceof Response)
		{
			$response = new Response($response);
			if( ! $response->headers->has('content-type'))
				$response->headers->set('content-type', 'text/html');
		}

		// If response is HTML parse it
		$contentType = $response->headers->get('content-type');
		if(str_contains($contentType, 'text/html'))
			$response->setContent(app('stolz.tidy')->parse($response->getContent()));

		return $response;
	}
}
