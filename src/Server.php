<?php

namespace Taupe\Vesta;


use Exception;

class Server
{
	private const ERROR_INDICATOR = 'Error: ';

	private $protocol = 'https';
	private $hostname;
	private $port = 8083;
	private $validate_ssl = true;
	private $username;
	private $password;

	public function __construct(string $hostname, string $username, string $password)
	{
		$this->hostname = $hostname;
		$this->username = $username;
		$this->password = $password;
	}

	/**
	 * @param string $protocol
	 */
	public function setProtocol(string $protocol): void
	{
		$this->protocol = $protocol;
	}

	/**
	 * @param int $port
	 */
	public function setPort(int $port): void
	{
		$this->port = $port;
	}

	/**
	 * @param bool $validate_ssl
	 */
	public function validateSsl(bool $validate_ssl): void
	{
		$this->validate_ssl = $validate_ssl;
	}

	public function doRequest(string $command, array $data): string
	{
		return $this->doCURL($this->generatePostData($command, $data));
	}

	public function doCall(string $command, array $data): int
	{
		$post = $this->generatePostData($command, $data);
		$post['returncode'] = 'yes';
		$return = $this->doCURL($post);
		return intval($return);
	}

	private function doCURL(array $data): string
	{
		$curl = curl_init(sprintf("%s://%s:%d/api/", $this->protocol, $this->hostname, $this->port));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, $this->validate_ssl ? 2 : 0);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

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

	public function getUsers(): array
	{
		$json = $this->doRequest("v-list-users", ["json"]);
		$users = json_decode($json, true);
		foreach ($users as $name => $data) {
			$users[$name] = User::fromArray($name, $data, $this);
		}
		return $users;
	}

	public function addUser(string $username, string $password, string $email): User
	{
		$return = $this->doCall('v-add-user', [$username, $password, $email]);
		if (!ReturnCode::isOkay($return)) {
			throw new Exception("Could not create user");
		}
		//TODO this could be made faster
		return $this->getUser($username);
	}

	public function getUser(string $username): User
	{
		$data = $this->doRequest("v-list-user", ["admin", "json"]);
		$json = json_decode($data, true);
		return User::fromArray($username, $json, $this);
	}
}
