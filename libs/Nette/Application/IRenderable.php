<?php

/**
 * This file is part of the Nette Framework (http://nette.org)
 *
 * Copyright (c) 2004 David Grudl (http://davidgrudl.com)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 * @package Nette\Application
 */



/**
 *
 *
 * @author     David Grudl
 * @package Nette\Application
 */
interface IRenderable
{

	/**
	 * Forces control to repaint.
	 * @param  string
	 * @return void
	 */
	function invalidateControl();

	/**
	 * Is required to repaint the control?
	 * @return bool
	 */
	function isControlInvalid();

}



/**
 *
 *
 * @author     David Grudl
 * @package Nette\Application
 */
interface IPartiallyRenderable extends IRenderable
{

	/**
	 * Forces control or its snippet to repaint.
	 * @param  string
	 * @return void
	 */
	//function invalidateControl($snippet = NULL);

	/**
	 * Is required to repaint the control or its snippet?
	 * @param  string  snippet name
	 * @return bool
	 */
	//function isControlInvalid($snippet = NULL);

}
