<?php
/**
 * User: Maciej Łebkowski
 * Date: 03.07.2012 14:57
 */
namespace Positionly;

interface HttpClientInterface
{
	public function request($url, array $params);
}