<?php

declare(strict_types=1);

namespace Crell\HttpTools;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;

class ResponseBuilder
{
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly StreamFactoryInterface $streamFactory,
    ) {
    }

    public function createResponse(int|HttpStatus $code, string|StreamInterface $body, ?string $contentType = null): ResponseInterface
    {
        if ($code instanceof HttpStatus) {
            $code = $code->value;
        }
        if (is_string($body)) {
            $body = $this->streamFactory->createStream($body);
            $body->rewind();
        }
        $response = $this->responseFactory
            ->createResponse($code)
            ->withBody($body);
        if ($contentType) {
            $response = $response->withHeader('content-type', $contentType);
        }

        return $response;
    }

    public function ok(string|StreamInterface $body, ?string $contentType = null): ResponseInterface
    {
        return $this->createResponse(HttpStatus::OK, $body, $contentType);
    }

    public function created(string $location): ResponseInterface
    {
        return $this->responseFactory
            ->createResponse(HttpStatus::Created->value)
            ->withHeader('location', $location);
    }

    public function noContent(): ResponseInterface
    {
        return $this->responseFactory
            ->createResponse(HttpStatus::NoContent->value);
    }

    public function notModified(): ResponseInterface
    {
        return $this->responseFactory
            ->createResponse(HttpStatus::NotModified->value);
    }

    public function seeOther(string $location): ResponseInterface
    {
        return $this->responseFactory
            ->createResponse(HttpStatus::SeeOther->value)
            ->withHeader('location', $location);
    }

    public function temporaryRedirect(string $location): ResponseInterface
    {
        return $this->responseFactory
            ->createResponse(HttpStatus::TemporaryRedirect->value)
            ->withHeader('location', $location);
    }

    public function permanentRedirect(string $location): ResponseInterface
    {
        return $this->responseFactory
            ->createResponse(HttpStatus::PermanentRedirect->value)
            ->withHeader('location', $location);
    }

    public function notFound(string|StreamInterface $body, ?string $contentType = null): ResponseInterface
    {
        return $this->createResponse(HttpStatus::NotFound->value, $body, $contentType);
    }

    public function forbidden(string|StreamInterface $body, ?string $contentType = null): ResponseInterface
    {
        return $this->createResponse(HttpStatus::Forbidden->value, $body, $contentType);
    }

    public function gone(string|StreamInterface $body, ?string $contentType = null): ResponseInterface
    {
        return $this->createResponse(HttpStatus::Gone->value, $body, $contentType);
    }

    /**
     * @param string[] $allowedMethods
     */
    public function methodNotAllowed(array $allowedMethods): ResponseInterface
    {
        return $this->responseFactory
            ->createResponse(HttpStatus::MethodNotAllowed->value)
            ->withHeader('allow', implode(', ', array_map(strtoupper(...), $allowedMethods)))
        ;
    }

    /**
     * @param string[] $allowedTypes
     */
    public function unsupportedMediaType(array $allowedTypes): ResponseInterface
    {
        return $this->responseFactory
            ->createResponse(HttpStatus::UnsupportedMediaType->value)
            ->withHeader('accept', implode(', ', $allowedTypes))
            ;
    }
}
