<?php

namespace Szenis\Picro;

use Szenis\Picro\Exceptions\RouteNotCallableException;
use Szenis\Picro\Container;

class ArgumentResolver
{
	/**
	 * @var Container
	 */
	private $container;

	/**
	 * Contains all the parameters for the current route
	 *
	 * @var array
	 */
	private $parameters = [];

	/**
	 * Regex pattern to determine class en method name
	 *
	 * @var string
	 */
	private $pattern = '~^([a-zA-Z0-9\\\]+)::([a-zA-Z0-9]+)$~';

	/**
	 * Classes that can be injected just by typehinting them in the callable
	 *
	 * @var array
	 */
	private $definedClasses = [	
		'Symfony\Component\HttpFoundation\Request' => 'request',
		'Symfony\Component\HttpFoundation\Response' => 'response',
	];

	/**
	 * @param Container $container
	 */
	public function __construct(Container $container)
	{
		$this->container = $container;
	}

	/**
	 * Resolve the arguments of the given route
	 *
	 * @param array $route
	 *
	 * @return array
	 */
	public function resolve(array $route)
	{
		$handler = $this->handleCallable($route['handler']);

		if (is_array($handler)) {
			$reflection = new \ReflectionMethod($handler[0], $handler[1]);
		} else {
			$reflection = new \ReflectionFunction($handler);
		}

		// loop trough all the parameters
		foreach ($reflection->getParameters() as $parameter) {
			// if the parameter exsists in our argument array we will add the argument
			if (array_key_exists($parameter->getName(), $route['arguments'])) {
				$this->parameters[] = $route['arguments'][$parameter->getName()];

				continue;
			}

			// if the parameter is typehinted
			if ($parameter->getClass() && $parameter->getClass()->getName()) {
				// get the class name
				$className = $parameter->getClass()->getName();

				// if the class name is one of the know classes we can inject them
				if (array_key_exists($className, $this->definedClasses)) {
					$this->parameters[] = $this->container->get($this->definedClasses[$className]);

					continue;
				}
			}

			// if the parameter couldnt be resolved we will throw an error
			throw new RouteNotCallableException("Unknown parameter or typehint! parameter: '".$parameter->getName() ."' not found!");
		}

		return [
			'handler' => $handler,
			'arguments' => $this->parameters,
		];
	}

	/**
	 * Resolves the handler into a callable
	 *
	 * @param  mixed $handler
	 *
	 * @return callable
	 *
	 * @throws RouteNotCallableException
	 */
	private function handleCallable($handler)
	{
		if ($handler instanceof \Closure) {
			return $handler;
		}

		if (is_callable($handler) && is_string($handler)) {
			if (preg_match($this->pattern, $handler, $matches)) {
				return [new $matches[1](), $matches[2]];
			}
		}

		throw new RouteNotCallableException("The given method for the current route is not callable!", 1);
	}
}
