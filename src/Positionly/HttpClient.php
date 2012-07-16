<?php
/**
 * User: Maciej Łebkowski
 * Date: 03.07.2012 14:55
 */

namespace Positionly;

class HttpClient implements HttpClientInterface
{
	public function request($url, array $params)
	{
		$c = curl_init($url);
		curl_setopt_array($c, array (
			CURLOPT_POST => true,
			# CURLOPT_PROXY => 'nassau.one.pl:444',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POSTFIELDS => http_build_query($params),
		));

		$content = curl_exec($c);
		return $content;
	}
}