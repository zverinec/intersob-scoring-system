<?php

/**
 * This file is part of the Nette Framework (http://nette.org)
 *
 * Copyright (c) 2004 David Grudl (http://davidgrudl.com)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 * @package Nette\Security
 */



/**
 * Trivial implementation of IAuthenticator.
 *
 * @author     David Grudl
 * @package Nette\Security
 */
class SimpleAuthenticator extends Object implements IAuthenticator
{
	/** @var array */
	private $userlist;


	/**
	 * @param  array  list of usernames and passwords
	 */
	public function __construct(array $userlist)
	{
		$this->userlist = $userlist;
	}



	/**
	 * Performs an authentication against e.g. database.
	 * and returns IIdentity on success or throws AuthenticationException
	 * @param  array
	 * @return IIdentity
	 * @throws AuthenticationException
	 */
	public function authenticate(array $credentials)
	{
		$username = $credentials[self::USERNAME];
		foreach ($this->userlist as $name => $pass) {
			if (strcasecmp($name, $credentials[self::USERNAME]) === 0) {
				if (strcasecmp($pass, $credentials[self::PASSWORD]) === 0) {
					// matched!
					return new Identity($name);
				}

				throw new AuthenticationException("Invalid password.", self::INVALID_CREDENTIAL);
			}
		}

		throw new AuthenticationException("User '$username' not found.", self::IDENTITY_NOT_FOUND);
	}

}
