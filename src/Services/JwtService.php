<?php

declare(strict_types=1);

namespace Kartalit\Services;

use Firebase\JWT\JWT;
use Kartalit\Config\Config;

class JwtService
{
    public function __construct(private Config $config)
    {
        date_default_timezone_set("Europe/Berlin");
    }

    public function jwtEncode(array $payload): string
    {
        $token = [
            "iss" => "Kartalit",
            "iat" => time(),
            "exp" => time() + 3600,
            "data" => $payload,
        ];
        return JWT::encode($token, $this->config->jwt["secret"], "HS256");
    }
}
