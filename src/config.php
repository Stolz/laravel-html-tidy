<?php

/*
|--------------------------------------------------------------------------
| HTML Tidy Laravel filter
|--------------------------------------------------------------------------
|
| Here is a list of all available config options with their default values.
|
| For more info please visit https://github.com/Stolz/laravel-html-tidy
|
*/
return [

	// Enable if PHP has tidy extension support
	'enabled' => extension_loaded('tidy'),

	// Process AJAX requests
	'ajax' => false,

	// Encoding of your original documents. This refers to the encoding that you original documents have,
	// not the enconding that will be used for the output.
	'encoding' => 'utf8', // Possible values: ascii, latin0, latin1, raw, utf8, iso2022, mac, win1252, ibm858, utf16, utf16le, utf16be, big5, and shiftjis.

	// Doctype used for the output
	'doctype' => '<!DOCTYPE html>',

	// Append errors to output
	'display_errors' => true,

	// Errors container opening tag
	'container_open_tag' => '<div id="tidy_errors" style="position: absolute;right: 0;top: 0;z-index: 100;padding:1em;margin:1em;border:1px solid #DC0024;font-family: Sans-Serif;background-color:#FFE5E5;color:#DC0024"><a style="float:right;cursor:pointer;color:blue;margin:-15px" onclick="document.getElementById(\'tidy_errors\').style.display = \'none\'">[x]</a>',

	// Errors container closing tag
	'container_close_tag' => '</div>',

	// Options passed to HTML Tidy parseString() function. Docs: http://tidy.sourceforge.net/docs/quickref.html
	'tidy_options' => [
		'output-xhtml' => true,
		'char-encoding' => 'utf8',
		'wrap' => 0,
		'wrap-sections' => false,
		'indent' => 2, // 2 is equivalent to 'auto', which seems to be ignored by PHP-html-tidy extension
		'indent-spaces' => 4,

		// HTML5 workarounds
		'doctype' => 'omit', //The filter will add the configured doctype later
		'new-blocklevel-tags' => 'article,aside,canvas,dialog,embed,figcaption,figure,footer,header,hgroup,nav,output,progress,section,video',
		'new-inline-tags' => 'audio,bdi,command,datagrid,datalist,details,keygen,mark,meter,rp,rt,ruby,source,summary,time,track,wbr',
	],

	// Errors that match these regexs wont be displayed
	'ignored_errors' => [
		// workaround to hide errors related to HTML5
		"/line.*<html> proprietary attribute \"class\"\n?/",
		"/line.*<input> proprietary attribute \"autocomplete\"\n?/",
		"/line.*<input> proprietary attribute \"autofocus\"\n?/",
		"/line.*<meta> lacks \"content\" attribute\n?/",
		"/line.*<meta> proprietary attribute \"charset\"\n?/",
		"/line.*<script> inserting \"type\" attribute\n?/",
		"/line.*<style> inserting \"type\" attribute\n?/",
		"/line.*<table> lacks \"summary\" attribute\n?/",
		"/line.*is not approved by W3C\n?/",
		"/line.*proprietary attribute \"data-.*\n?/",
		"/line.*proprietary attribute \"placeholder.*\n?/",
		"/line.*proprietary attribute \"contenteditable.*\n?/",
	],

];
