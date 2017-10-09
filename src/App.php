<?php

namespace Szenis\Picro;

use Szenis\Picro\Container;
use Szenis\Picro\Exceptions\RouteNotFoundException;
use Szenis\Picro\Exceptions\RouteNotCallableException;
use Symfony\Component\HttpFoundation\Response;

class App
{
	/**
	 * @var Container
	 */
	private $container;

	/**
	 * Initialize the container
	 *
	 * @param array $values
	 */
	public function __construct(array $values = [])
	{
		$this->container = new Container($values);
	}

	/**
	 * @return void
	 */
	public function run()
	{
		$response = $this->resolveRoute();

		if (!($response instanceof Response)) {
			throw new \LogicException('The controller must return a \Symfony\Component\HttpFoundation\Response object');
		}

		$response->send();
	}

	/**
	 * @param mixed  $callable
	 * @param string $slug
	 *
	 * @return App
	 */
	public function get($slug, $callable)
	{
		$this->container->get('router')->get($slug, $callable);
	
		return $this;
	}

	/**
	 * @param mixed  $callable
	 * @param string $slug
	 *
	 * @return App
	 */
	public function put($slug, $callable)
	{
		$this->container->get('router')->put($slug, $callable);
	
		return $this;
	}

	/**
	 * @param mixed  $callable
	 * @param string $slug
	 *
	 * @return App
	 */
	public function post($slug, $callable)
	{
		$this->container->get('router')->post($slug, $callable);
	
		return $this;
	}

	/**
	 * @param mixed  $callable
	 * @param string $slug
	 *
	 * @return App
	 */
	public function patch($slug, $callable)
	{
		$this->container->get('router')->patch($slug, $callable);
	
		return $this;
	}

	/**
	 * @param mixed  $callable
	 * @param string $slug
	 *
	 * @return App
	 */
	public function delete($slug, $callable)
	{
		$this->container->get('router')->delete($slug, $callable);
	
		return $this;
	}

	/**
	 * Resolve the route and execute it
	 * Return the response of the action
	 *
	 * @throws RouteNotCallableException
	 * @throws RouteNotFoundException
	 *
	 * @return mixed
	 */
	private function resolveRoute()
	{
		$request = $this->container->get('request');
		$router = $this->container->get('router');

		$route = $router->resolve($request->getPathInfo(), $request->getMethod());

		if ($route['code'] !== \Szenis\Routing\Route::STATUS_FOUND) {
			throw new RouteNotFoundException("Route with uri '".$request->getUri()."' not found");
		}

		$resolver = new ArgumentResolver($this->container);
		$route = $resolver->resolve($route);

		return call_user_func_array($route['handler'], $route['arguments']);
	}
}
