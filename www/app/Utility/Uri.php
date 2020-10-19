<?php

namespace App\Utility;

/**
 * URI:
 *
 * @author John Alex
 * @since 1.0.7
 */
class Uri {

	/**
	 * Returns the full uri as a string
	 * @access public
	 * @return  string
	 */
	public static function string() {
		if ($request = Request::active()) {
			return $request->uri->get();
		}

		return null;
	}

	/**
	 * Creates a url with the given uri, including the base url
	 * @access public
	 * @param   string  $uri            The uri to create the URL for
	 * @param   array   $variables      Some variables for the URL
	 * @param   array   $get_variables  Any GET urls to append via a query string
	 * @param   bool    $secure         If false, force http. If true, force https
	 * @return  string
	 */
	public static function create($uri = null, $variables = array(), $get_variables = array(), $secure = null) {

		$url = '';
		is_null($uri) and $uri = static::string();

		// If the given uri is not a full URL
		if( ! preg_match("#^(http|https|ftp)://#i", $uri)) {
			$url .= Config::get('base_url');

			if ($index_file = Config::get('index_file')) {
				$url .= $index_file.'/';
			}
		}
		$url .= ltrim($uri, '/');

		// stick a url suffix onto it if defined and needed
		if ($url_suffix = Config::get('url_suffix', false) and substr($url, -1) != '/') {
			$current_suffix = strrchr($url, '.');
			if ( ! $current_suffix or strpos($current_suffix, '/') !== false) {
				$url .= $url_suffix;
			}
		}

		if ( ! empty($get_variables)) {
			$char = strpos($url, '?') === false ? '?' : '&';
			if (is_string($get_variables)) {
				$url .= $char.str_replace('%3A', ':', $get_variables);
			} else {
				$url .= $char.str_replace('%3A', ':', http_build_query($get_variables));
			}
		}

		array_walk(
			$variables,
			function ($val, $key) use (&$url) {
				$url = str_replace(':'.$key, $val, $url);
			}
		);

		is_bool($secure) and $url = http_build_url($url, array('scheme' => $secure ? 'https' : 'http'));

		return $url;
	}
}
