<?php

/**
 * This file is part of the Nette Framework (http://nette.org)
 *
 * Copyright (c) 2004 David Grudl (http://davidgrudl.com)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 * @package Nette\Forms
 */



/**
 * Push button control with no default behavior.
 *
 * @author     David Grudl
 * @package Nette\Forms
 */
class Button extends FormControl
{

	/**
	 * @param  string  caption
	 */
	public function __construct($caption = NULL)
	{
		parent::__construct($caption);
		$this->control->type = 'button';
	}



	/**
	 * Bypasses label generation.
	 * @return void
	 */
	public function getLabel($caption = NULL)
	{
		return NULL;
	}



	/**
	 * Generates control's HTML element.
	 * @param  string
	 * @return Html
	 */
	public function getControl($caption = NULL)
	{
		$control = parent::getControl();
		$control->value = $this->translate($caption === NULL ? $this->caption : $caption);
		return $control;
	}

}
