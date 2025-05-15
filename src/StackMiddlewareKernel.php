<?php

declare(strict_types=1);

namespace Crell\HttpTools;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * A simple middleware stack kernel.
 *
 * Add middlewares "inside out."  That is, the first middleware added will get the request last, and the response first.
 */
class StackMiddlewareKernel implements RequestHandlerInterface
{
    private RequestHandlerInterface $tip;

    public function __construct(
        RequestHandlerInterface $baseHandler,
    ) {
        $this->tip = $baseHandler;
    }

    public function addMiddleware(MiddlewareInterface $middleware): self
    {
        $this->tip = new PassthruHandler($middleware, $this->tip);
        return $this;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->tip->handle($request);
    }
}
