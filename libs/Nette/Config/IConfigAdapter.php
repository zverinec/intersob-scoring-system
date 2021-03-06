<?php

/**
 * This file is part of the Nette Framework (http://nette.org)
 *
 * Copyright (c) 2004 David Grudl (http://davidgrudl.com)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 * @package Nette\Config
 */



/**
 * Adapter for reading and writing configuration files.
 *
 * @author     David Grudl
 * @package Nette\Config
 */
interface IConfigAdapter
{

	/**
	 * Reads configuration from file.
	 * @param  string  file name
	 * @param  string  section to load
	 * @return array
	 */
	static function load($file, $section = NULL);

	/**
	 * Writes configuration to file.
	 * @param  Config to save
	 * @param  string  file
	 * @param  string  section name
	 * @return void
	 */
	static function save($config, $file, $section = NULL);

}
