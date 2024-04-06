<?php

namespace Ihor\MicroFramework\Routes;

use Ihor\MicroFramework\Http\Request;

interface RouteInterface
{

    public function dispatch(Request $request);
}