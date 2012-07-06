<?php

require 'vendor/autoload.php';

$response = \Positionly\Api::create(getenv('PLY_SECRET'), 'http://www.znanylekarz.pl')
	->sendRequest('Beata kaczmarek lekarz', 'http://nassau.one.pl:8080/?q=:keyword');

var_dump($response);