<?php

namespace Igarevv\Micrame\Enums;

enum HttpEnum: int
{

    case OK = 200;
    case SERVER_ERROR = 500;
    case UNAUTHORIZED = 401;
    case BAD_REQUEST = 400;
    case FORBIDDEN = 403;

    public function toString(): string
    {
        return match ($this) {
            self::SERVER_ERROR => '500 | Server Error',
            self::UNAUTHORIZED => 'Authentication failed',
            self::BAD_REQUEST => 'Bad request data provided',
            self::FORBIDDEN => 'Forbidden',
            default => 'OK'
        };
    }

}
