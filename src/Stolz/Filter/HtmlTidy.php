<?php namespace Stolz\Filter;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class HtmlTidy
{

	/**
	 * Whether or not the filter is globally enabled.
	 * @var bool
	 */
	protected $enabled = true;

	/**
	 * Whether or not filter AJAX requests.
	 * @var bool
	 */
	protected $ajax = false;

	/**
	 * Encoding of the original documents.
	 * Possible values: ascii, latin0, latin1, raw, utf8, iso2022, mac, win1252, ibm858, utf16, utf16le, utf16be, big5, and shiftjis.
	 * @var string
	 */
	protected $encoding = 'utf8';

	/**
	 * Doctype used for the output.
	 * @var string
	 */
	protected $doctype = '<!DOCTYPE html>';

	/**
	 * Whether or not append errors to output.
	 * @var bool
	 */
	protected $display_errors = true;

	/**
	 * Errors container opening tag
	 * @var string
	 */
	protected $container_open_tag = '<div id="tidy_errors" style="position: absolute;right: 0;top: 0;z-index: 100;padding:1em;margin:1em;border:1px solid #DC0024;font-family: Sans-Serif;background-color:#FFE5E5;color:#DC0024"><a style="float:right;cursor:pointer;color:blue;margin:-15px" onclick="document.getElementById(\'tidy_errors\').style.display = \'none\'">[x]</a>';

	/**
	 * Errors container closing tag
	 * @var string
	 */
	protected $container_close_tag = '</div>';

	/**
	 * Options passed to HTML Tidy parseString() function.
	 * Please read http://tidy.sourceforge.net/docs/quickref.html
	 * @var array
	 */
	protected $tidy_options = array(
		'output-xhtml' => true,
		'char-encoding' => 'utf8',
		//'hide-comments' => true,
		'wrap' => 0,
		'wrap-sections' => false,
		'indent' => 2, // 2 is equivalent to 'auto', which seems to be ignored by PHP-html-tidy extension
		'indent-spaces' => 4,

		// HTML5 workarounds
		'doctype' => 'omit', //The filter will add the configured doctype later
		'new-blocklevel-tags' => 'article,aside,canvas,dialog,embed,figcaption,figure,footer,header,hgroup,nav,output,progress,section,video',
		'new-inline-tags' => 'audio,bdi,command,datagrid,datalist,details,keygen,mark,meter,rp,rt,ruby,source,summary,time,track,wbr',
	);

	/**
	 * Errors that match these regexs wont be displayed
	 * @var array
	 */
	protected $ignored_errors = array(
		// workaround to hide errors related to HTML5
		"/line.*proprietary attribute \"data-.*\n?/",
		"/line.*proprietary attribute \"placeholder.*\n?/",
		"/line.*is not approved by W3C\n?/",
		"/line.*<html> proprietary attribute \"class\"\n?/",
		"/line.*<meta> proprietary attribute \"charset\"\n?/",
		"/line.*<meta> lacks \"content\" attribute\n?/",
		"/line.*<table> lacks \"summary\" attribute\n?/",
		"/line.*<style> inserting \"type\" attribute\n?/",
		"/line.*<script> inserting \"type\" attribute\n?/",
		"/line.*<input> proprietary attribute \"autocomplete\"\n?/",
		"/line.*<input> proprietary attribute \"autofocus\"\n?/",
	);

	/**
	 * Class constructor.
	 *
	 * @param  array $options
	 * @return void
	 */
	public function __construct(array $options = array())
	{
		if($options)
			$this->config($options);
	}

	/**
	 * Set config options
	 *
	 * @param  array $options
	 * @return void
	 */
	public function config(array $config)
	{
		foreach ($config as $key => $value)
		{
			if(property_exists($this, $key))
				$this->$key = $value;
		}
	}

	/**
	 * Determine whether or not the response should be filtered.
	 *
	 * @param  \Illuminate\Http\Request   $request
	 * @param  \Illuminate\Http\Response  $response

	 * @return boolean
	 */
	protected function filterable(Request $request, Response $response)
	{
		if ( ! $this->enabled or ($request->ajax() and ! $this->ajax))
			return false;

		return (strpos($response->headers->get('content-type'), 'text/html') !== false);
	}

	/**
	 * Parse resquest response with PHP HTML Tidy extension
	 *
	 * @param  \Illuminate\Routing\Route  $route
	 * @param  \Illuminate\Http\Request   $request
	 * @param  \Illuminate\Http\Response  $response
	 * @return \Illuminate\Http\Response|null
	 */
	public function filter($route, Request $request, Response $response)
	{

		if ( ! $this->filterable($request, $response))
			return null;

		// Parse output
		$tidy = new \tidy;
		$tidy->parseString($response->getContent(), $this->tidy_options, $this->encoding);
		$tidy->cleanRepair();

		// Set doctype
		$output = $this->doctype . "\n" . preg_replace('_ xmlns="http://www.w3.org/1999/xhtml"_', null, $tidy, 1);

		// Append errors
		if ($this->display_errors and $tidy->getStatus())
		{
			// Omit ignored errors
			$errors = $tidy->errorBuffer;
			foreach($this->ignored_errors as $regex)
				$errors = preg_replace($regex, null, $errors);

			// Wrap errors in a container
			if (strlen($errors))
				$errors = $this->container_open_tag . nl2br(htmlentities($errors)) . $this->container_close_tag;

			// Append errors at the end of the document
			$output = str_replace('</body>', $errors . '</body>', $output);
		}

		// Render output
		$response->setContent($output);

		return $response;
	}
}
