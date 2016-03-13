<?php

/**
 * This file is part of the Nette Framework (http://nette.org)
 *
 * Copyright (c) 2004 David Grudl (http://davidgrudl.com)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 * @package Nette
 */



/**
 * The Nette Framework (http://nette.org)
 *
 * @author     David Grudl
 * @package Nette
 */
final class Framework
{

	/**#@+ Nette Framework version identification */
	const NAME = 'Nette Framework';

	const VERSION = '0.9.7';

	const REVISION = 'f0ec895 released on 2012-01-20';
	/**#@-*/



	/**
	 * Static class - cannot be instantiated.
	 */
	final public function __construct()
	{
		throw new LogicException("Cannot instantiate static class " . get_class($this));
	}



	/**
	 * Compares current Nette Framework version with given version.
	 * @param  string
	 * @return int
	 */
	public static function compareVersion($version)
	{
		return version_compare($version, self::VERSION);
	}



	/**
	 * Nette Framework promotion.
	 * @return void
	 */
	public static function promo($xhtml = TRUE)
	{
		echo '<a href="http://nette.org/" title="Nette Framework - The Most Innovative PHP Framework"><img ',
			'src="http://files.nette.org/icons/nette-powered.gif" alt="Powered by Nette Framework" width="80" height="15"',
			($xhtml ? ' />' : '>'), '</a>';
	}

}
