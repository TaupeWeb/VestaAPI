<?php

namespace Taupe\Vesta;

class Server
{
	private $hostname;
	private $username;
	private $password;

	public function __construct(string $hostname, string $username, string $password)
	{
		$this->hostname = $hostname;
		$this->username = $username;
		$this->password = $password;
	}
}
