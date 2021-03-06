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
 * Tools library.
 *
 * @author     David Grudl
 * @package Nette
 */
final class Tools
{
	/** minute in seconds */
	const MINUTE = 60;

	/** hour in seconds */
	const HOUR = 3600;

	/** day in seconds */
	const DAY = 86400;

	/** week in seconds */
	const WEEK = 604800;

	/** average month in seconds */
	const MONTH = 2629800;

	/** average year in seconds */
	const YEAR = 31557600;



	/**
	 * Static class - cannot be instantiated.
	 */
	final public function __construct()
	{
		throw new LogicException("Cannot instantiate static class " . get_class($this));
	}



	/**
	 * DateTime object factory.
	 * @param  string|int|DateTime
	 * @return DateTime53
	 */
	public static function createDateTime($time)
	{
		if ($time instanceof DateTime) {
			return clone $time;

		} elseif (is_numeric($time)) {
			if ($time <= self::YEAR) {
				$time += time();
			}
			return new DateTime(date('Y-m-d H:i:s', $time));

		} else { // textual or NULL
			return new DateTime($time);
		}
	}



	/**
	 * Gets the boolean value of a configuration option.
	 * @param  string  configuration option name
	 * @return bool
	 */
	public static function iniFlag($var)
	{
		$status = strtolower(ini_get($var));
		return $status === 'on' || $status === 'true' || $status === 'yes' || $status % 256;
	}



	/**
	 * Initializes variable with $default value.
	 * @param  mixed  variable
	 * @param  mixed  default value
	 * @return void
	 */
	public static function defaultize(&$var, $default)
	{
		if ($var === NULL) $var = $default;
	}



	/**
	 * Recursive glob(). Finds pathnames matching a pattern.
	 * @param  string
	 * @param  int
	 * @return array
	 */
	public static function glob($pattern, $flags = 0)
	{
		// TODO: replace by RecursiveDirectoryIterator
		$files = glob($pattern, $flags);
		if (!is_array($files)) {
			$files = array();
		}

		$dirs = glob(dirname($pattern) . '/*', $flags | GLOB_ONLYDIR);
		if (is_array($dirs)) {
			$mask = basename($pattern);
			foreach ($dirs as $dir) {
				$files = array_merge($files, self::glob($dir . '/' . $mask, $flags));
			}
		}

		return $files;
	}



	/**
	 * Returns the MIME content type of file.
	 * @param  string
	 * @return string
	 */
	public static function detectMimeType($file)
	{
		if (!is_file($file)) {
			throw new FileNotFoundException("File '$file' not found.");
		}

		$info = @getimagesize($file); // @ - files smaller than 12 bytes causes read error
		if (isset($info['mime'])) {
			return $info['mime'];

		} elseif (extension_loaded('fileinfo')) {
			$type = preg_replace('#[\s;].*$#', '', finfo_file(finfo_open(FILEINFO_MIME), $file));

		} elseif (function_exists('mime_content_type')) {
			$type = mime_content_type($file);
		}

		return isset($type) && preg_match('#^\S+/\S+$#', $type) ? $type : 'application/octet-stream';
	}



	/********************* errors and warnings catching ****************d*g**/



	/** @var string */
	private static $errorMsg;



	/**
	 * Starts catching potential errors/warnings.
	 * @return void
	 */
	public static function tryError($level = E_ALL)
	{
		set_error_handler(array(__CLASS__, '_errorHandler'), $level);
		self::$errorMsg = NULL;
	}



	/**
	 * Returns catched error/warning message.
	 * @param  string  catched message
	 * @return bool
	 */
	public static function catchError(& $message)
	{
		restore_error_handler();
		$message = self::$errorMsg;
		self::$errorMsg = NULL;
		return $message !== NULL;
	}



	/**
	 * Internal error handler. Do not call directly.
	 * @internal
	 */
	public static function _errorHandler($severity, $message)
	{
		if (($severity & error_reporting()) !== $severity) {
			return NULL;
		}

		if (ini_get('html_errors')) {
			$message = html_entity_decode(strip_tags($message), ENT_QUOTES, 'UTF-8');
		}

		if (($a = strpos($message, ': ')) !== FALSE) {
			$message = substr($message, $a + 2);
		}

		self::$errorMsg = $message;
	}

}
