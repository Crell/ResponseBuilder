<?php

declare(strict_types=1);

namespace Crell\HttpTools;

enum HttpStatus: int
{
    case OK = 200;
    case Created = 201;
    case NoContent = 204;

    case SeeOther = 303;
    case NotModified = 304;
    case TemporaryRedirect = 307;
    case PermanentRedirect = 308;

    case Forbidden = 403;
    case NotFound = 404;
    case MethodNotAllowed = 405;

    /** Indicates the server cannot handle the Accept header */
    case NotAcceptable = 406;
    case Gone = 410;
    /** Indicates the server cannot handle the Content-Type header */
    case UnsupportedMediaType = 415;
}
