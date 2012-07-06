<?php
/**
 * User: Maciej Łebkowski
 * Date: 03.07.2012 14:59
 */

namespace Positionly;

class Api
{

	const ENGINE_PROVIDER_GOOGLE = 'Google';
	const ENGINE_DOMAIN_GOOGLE_PL = 'www.google.pl';
	const LANGUAGE_CODE_PL = 'pl';

	const STATUS_OK = 'ok';
	const STATUS_ERROR = 'error';

	protected $secret;
	/**
	 * @var HttpClientInterface
	 */
	protected $httpClient;
	protected $websiteName;
	protected $engineProvider = self::ENGINE_PROVIDER_GOOGLE;
	protected $engineDomain = self::ENGINE_DOMAIN_GOOGLE_PL;
	protected $languageCode = self::LANGUAGE_CODE_PL;

	const ENDPOINT = 'http://api.positionly.com/v1/custom/update.json';

	public function __construct($secret, $websiteName = null)
	{
		$this->setSecret($secret);
		if ($websiteName)
		{
			$this->setWebsiteName($websiteName);
		}
	}
	public static function create($secret, $websiteName = null)
	{
		$self = new self($secret, $websiteName);
		return $self;
	}

	public function sendRequest($keyword, $callbackUrl)
	{
		$callbackUrl = str_replace(':keyword', urlencode($keyword), $callbackUrl);

		$params = array (
			'keyword_name' => $keyword,
			'return_url' => $callbackUrl,
			'website_name' => $this->getWebsiteName(),
			'engine_provider' => $this->getEngineProvider(),
			'engine_domain' => $this->getEngineDomain(),
			'language_code' => $this->getLanguageCode(),
		);

		$params['sig'] = $this->signRequest($params);

		$httpClient = $this->getHttpClient();

		$response = $httpClient->request(self::ENDPOINT, $params);

		$response = $this->parseResponse($response);



		if (self::STATUS_OK !== $response->status)
		{
			throw new Exception('Api error: ' . $response->message);
		}
		return $response;
	}

	public function parseResponse($response)
	{
		$object = json_decode($response);
		if (null === $object)
		{
			throw new Exception('Response couldn’t be parsed');
		}
		return $object;
	}


	public function signRequest(array $params)
	{
		foreach ($params as $key => $value)
		{
			$params[$key] = $key . '=' . $value;
		}
		ksort($params);

		$params = implode('&', $params);

		return md5($params . $this->secret);
	}


	public function getSecret()
	{
		return $this->secret;
	}

	public function setSecret($secret)
	{
		$this->secret = $secret;
		return $this;
	}

	public function getHttpClient()
	{
		if (null === $this->httpClient)
		{
			$this->httpClient = new HttpClient();
		}
		return $this->httpClient;
	}

	public function setHttpClient(HttpClientInterface $httpClient)
	{
		$this->httpClient = $httpClient;
		return $this;
	}


	public function getWebsiteName()
	{
		return $this->websiteName;
	}

	public function setWebsiteName($websiteName)
	{
		$this->websiteName = $websiteName;
		return $this;
	}

	public function getEngineProvider()
	{
		return $this->engineProvider;
	}

	public function setEngineProvider($engineProvider)
	{
		$this->engineProvider = $engineProvider;
		return $this;
	}

	public function getEngineDomain()
	{
		return $this->engineDomain;
	}
	public function setEngineDomain($engineDoman)
	{
		$this->engineDomain = $engineDoman;
		return $this;
	}

	public function getLanguageCode()
	{
		return $this->languageCode;
	}

	public function setLanguageCode($languageCode)
	{
		$this->languageCode = $languageCode;
		return $this;
	}


}