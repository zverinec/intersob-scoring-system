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
 * The router broker.
 *
 * @author     David Grudl
 * @package Nette\Application
 */
class MultiRouter extends ArrayList implements IRouter
{
	/** @var array */
	private $cachedRoutes;



	public function __construct()
	{
		parent::__construct(NULL, 'IRouter');
	}



	/**
	 * Maps HTTP request to a PresenterRequest object.
	 * @param  IHttpRequest
	 * @return PresenterRequest|NULL
	 */
	public function match(IHttpRequest $httpRequest)
	{
		foreach ($this as $route) {
			$appRequest = $route->match($httpRequest);
			if ($appRequest !== NULL) {
				return $appRequest;
			}
		}
		return NULL;
	}



	/**
	 * Constructs absolute URL from PresenterRequest object.
	 * @param  IHttpRequest
	 * @param  PresenterRequest
	 * @return string|NULL
	 */
	public function constructUrl(PresenterRequest $appRequest, IHttpRequest $httpRequest)
	{
		if ($this->cachedRoutes === NULL) {
			$routes = array();
			$routes['*'] = array();

			foreach ($this as $route) {
				$presenter = $route instanceof Route ? $route->getTargetPresenter() : NULL;

				if ($presenter === FALSE) continue;

				if (is_string($presenter)) {
					$presenter = strtolower($presenter);
					if (!isset($routes[$presenter])) {
						$routes[$presenter] = $routes['*'];
					}
					$routes[$presenter][] = $route;

				} else {
					foreach ($routes as $id => $foo) {
						$routes[$id][] = $route;
					}
				}
			}

			$this->cachedRoutes = $routes;
		}

		$presenter = strtolower($appRequest->getPresenterName());
		if (!isset($this->cachedRoutes[$presenter])) $presenter = '*';

		foreach ($this->cachedRoutes[$presenter] as $route) {
			$uri = $route->constructUrl($appRequest, $httpRequest);
			if ($uri !== NULL) {
				return $uri;
			}
		}

		return NULL;
	}

}
