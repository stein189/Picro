<?php

namespace Szenis\Picro;

use Pimple\Container as BaseContainer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Szenis\Routing\Router;

/**
 * Container
 */
class Container extends BaseContainer
{
	/**
	 * On create container, register the required services
	 */
	public function __construct(array $values = [])
	{
		parent::__construct($values);

		$this->registerDefaultServices();
	}

	/**
	 * @param  string $id
	 *
	 * @return mixed
	 */
	public function get($id)
	{
		return $this->offsetGet($id);
	}

	/**
	 * Register all the services that are required for the framework
	 *
	 * @return void
	 */
	private function registerDefaultServices()
	{
		// register request object
	   	$this['request'] = function ($container) {
            return Request::createFromGlobals();
        };

        // register response object
        $this['response'] = function ($container) {
        	return new Response('', Response::HTTP_OK, array(
		    	'content-type' => 'text/html'
		    ));
        };

        // register the router
        $this['router'] = function ($container) {
        	return new Router();
        };
	}
}
