<?php

declare(strict_types=1);

namespace Kartalit\Services;

use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Kartalit\Config\Config;
use Kartalit\Errors\ExpiredTokenException;
use Kartalit\Errors\InvalidTokenException;
use Kartalit\Interfaces\TokenServiceInterface;
use Kartalit\Schemas\TokenPayload;
use LogicException;
use UnexpectedValueException;

class JwtService implements TokenServiceInterface
{
    public function __construct(private Config $config)
    {
        date_default_timezone_set("Europe/Berlin");
    }

    public function encodeToken(TokenPayload $payload, int $expirationTime = 3600): string
    {
        $token = [
            "iss" => "Kartalit",
            "iat" => time(),
            "exp" => time() + $expirationTime,
            "data" => $payload->getArray(),
        ];
        return JWT::encode($token, $this->config->jwt["secret"], "HS256");
    }

    public function decodeToken(string $jwt): TokenPayload
    {
        try {
            $decodedJwt = JWT::decode($jwt, new Key($this->config->jwt["secret"], "HS256"));
            return  TokenPayload::readFromObject($decodedJwt->data);
        } catch (\Throwable $th) {
            switch (get_class($th)) {
                case ExpiredException::class:
                    throw new ExpiredTokenException();
                case UnexpectedValueException::class:
                case LogicException::class:
                    throw new InvalidTokenException(previous: $th);
                default:
                    throw $th;
            }
        }
    }
    public function updateToken(string $jwt, ?int $expirationTime = 3600): string
    {
        $decodedPayload = $this->decodeToken($jwt);
        return $this->encodeToken($decodedPayload, $expirationTime);
    }
}
