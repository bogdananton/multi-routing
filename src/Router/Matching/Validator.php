<?php
namespace MultiRouting\Router\Matching;

use MultiRouting\Router\Request;
use MultiRouting\Router\Route;

interface Validator
{

    /**
     * Validate a given rule against a route and request.
     *
     * @param Route $route            
     * @param Request $request            
     * @return bool
     */
    public function matches(Route $route, Request $request);
}
