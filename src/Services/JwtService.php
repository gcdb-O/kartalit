<?php

declare(strict_types=1);

namespace Kartalit\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Kartalit\Config\Config;

class JwtService
{
    public function __construct(private Config $config)
    {
        date_default_timezone_set("Europe/Berlin");
    }

    public function jwtEncode(array $payload, int $expirationTime = 3600): string
    {
        $token = [
            "iss" => "Kartalit",
            "iat" => time(),
            "exp" => time() + $expirationTime,
            "data" => $payload,
        ];
        return JWT::encode($token, $this->config->jwt["secret"], "HS256");
    }

    public function jwtDecode(string $jwt): array
    {
        $decodedJwt = JWT::decode($jwt, new Key($this->config->jwt["secret"], "HS256"));
        return (array) $decodedJwt->data;
    }
}
