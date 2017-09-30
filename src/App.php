<?php

namespace Szenis\Picro;

use Szenis\Picro\Container;
use Szenis\RouteResolver;

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
		$request = $this->container->get('request');
		$resolver = new RouteResolver($this->container->get('router'));

		$result = $resolver->resolve([
			'method' => $request->getMethod(),
			'uri' => $request->getUri(),
		]);

		$this->container->get('response')->setContent($result)->send();
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
}
