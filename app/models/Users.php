<?php
/**
 * @author		Jan Papousek
 * @version 	2009-02-08
 */
class Users implements IAuthenticator {
	
	public function authenticate(array $credentials) {
		$username = $credentials[self::USERNAME];
		
		$row = dibi::query("SELECT password FROM ".Intersob::DATABASE_PREFIX.Intersob::DATABASE_TABLE_USERS." WHERE name = %s", $username)->fetch();
		
			
		if (!$row) {
			throw new AuthenticationException("Uživatel '$username' nebyl nalezen.", self::IDENTITY_NOT_FOUND);
		}

		if ($row->password !== sha1($credentials[self::PASSWORD])) {
			throw new AuthenticationException("Heslo nesouhlasí.", self::INVALID_CREDENTIAL);
		}

		unset($row->password);
		return new /*Nette\Security\*/Identity($username, array(), $row);
	}
}