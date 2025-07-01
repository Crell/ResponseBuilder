<?php

declare(strict_types=1);

namespace Crell\HttpTools\Router;

use Psr\Http\Message\ServerRequestInterface;

interface Router
{
    public function route(ServerRequestInterface $request): RouteResult;
}
