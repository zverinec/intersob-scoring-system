<?php

/**
 * This file is part of the Nette Framework (http://nette.org)
 *
 * Copyright (c) 2004 David Grudl (http://davidgrudl.com)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 * @package Nette\Templates
 */



/**
 * Defines template methods.
 *
 * @author     David Grudl
 * @package Nette\Templates
 */
interface ITemplate
{

	/**
	 * Renders template to output.
	 * @return void
	 */
	function render();

	/**
	 * Renders template to string.
	 * @return string
	 */
	//function __toString();

}
