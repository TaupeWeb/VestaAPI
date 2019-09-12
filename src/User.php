<?php

namespace Taupe\Vesta;


class User
{
	public static function fromArray(string $username, array $data, Server $server): User
	{
		$user = new User();
		$user->server = $server;
		$user->username = $username;
		$user->firstName = $data["FNAME"];
		$user->lastName = $data["LNAME"];

		return $user;
	}

	/** @var Server */
	private $server;
	/** @var string */
	private $username;
	/** @var string */
	private $firstName;
	/** @var string */
	private $lastName;

	/**
	 * @return string
	 */
	public function getUsername(): string
	{
		return $this->username;
	}

	/**
	 * @return string
	 */
	public function getFirstName(): string
	{
		return $this->firstName;
	}

	/**
	 * @return string
	 */
	public function getLastName(): string
	{
		return $this->lastName;
	}
}
