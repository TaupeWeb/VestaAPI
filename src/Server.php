<?php

namespace Taupe\Vesta;


use Exception;

class Server
{
	private const ERROR_INDICATOR = 'Error: ';

	private $hostname;
	private $username;
	private $password;

	public function __construct(string $hostname, string $username, string $password)
	{
		$this->hostname = $hostname;
		$this->username = $username;
		$this->password = $password;
	}

	public function doRequest(string $command, array $data): string
	{
		$curl = curl_init(sprintf("https://%s:8083/api/", $this->hostname));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $this->generatePostData($command, $data));

		$response = curl_exec($curl);
		curl_close($curl);

		$this->maybeThrow($response);
		return $response;
	}

	private function generatePostData(string $command, array $params): array
	{
		$data = [
			'user' => $this->username,
			'password' => $this->password,
			'cmd' => $command
		];
		for ($i = 0; $i < count($params); $i++) {
			$data["arg" . ($i + 1)] = $params[$i];
		}

		return $data;
	}

	/**
	 * Throws an exception when the response indicates an exception occured
	 * @param string $response
	 * @throws Exception
	 */
	private function maybeThrow(string $response): void
	{
		$matchLength = strlen(self::ERROR_INDICATOR);
		if (substr($response, 0, $matchLength) == self::ERROR_INDICATOR) {
			throw new Exception(substr($response, $matchLength));
		}
	}
}
