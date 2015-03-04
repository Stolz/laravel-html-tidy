<?php namespace Stolz\HtmlTidy;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
		$response = $next($request);

		// Check PHP extension
		if( ! extension_loaded('tidy') or ! config('tidy.enabled'))
			return $response;

		// Skip special response types
		if(($response instanceof BinaryFileResponse) or
		($response instanceof JsonResponse) or
		($response instanceof RedirectResponse) or
		($response instanceof StreamedResponse))
			return $response;

		// Check request
		if($request->ajax() and ! config('tidy.ajax'))
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
