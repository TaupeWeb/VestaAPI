<?php

namespace Taupe\Vesta;


class ReturnCode
{
	// Command has been successfuly performed
	public const OK = 0;
	// Not enough arguments provided
	public const E_ARGS = 1;
	// Object or argument is not valid
	public const E_INVALID = 2;
	// Object doesn't exist
	public const E_NOTEXIST = 3;
	// Object already exists
	public const E_EXISTS = 4;
	// Object is suspended
	public const E_SUSPENDED = 5;
	// Object is already unsuspended
	public const E_UNSUSPENDED = 6;
	// Object can't be deleted because is used by the other object
	public const E_INUSE = 7;
	// Object cannot be created because of hosting package limits
	public const E_LIMIT = 8;
	// Wrong password
	public const E_PASSWORD = 9;
	// Object cannot be accessed be the user
	public const E_FORBIDEN = 10;
	// Subsystem is disabled
	public const E_DISABLED = 11;
	// Configuration is broken
	public const E_PARSING = 12;
	// Not enough disk space to complete the action
	public const E_DISK = 13;
	// Server is to busy to complete the action
	public const E_LA = 14;
	// Connection failed. Host is unreachable
	public const E_CONNECT = 15;
	// FTP server is not responding
	public const E_FTP = 16;
	// Database server is not responding
	public const E_DB = 17;
	// RRDtool failed to update the database
	public const E_RRD = 18;
	// Update operation failed
	public const E_UPDATE = 19;
	// Service restart failed
	public const E_RESTART = 20;

	public static function isOkay(int $code)
	{
		return $code === self::OK;
	}
}
