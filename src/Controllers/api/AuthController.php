<?php

declare(strict_types=1);

namespace Kartalit\Controllers\api;

use Kartalit\Config\Config;
use Kartalit\Services\JwtService;
use Kartalit\Services\UsuariService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthController
{
    public function __construct(
        private UsuariService $usuariService,
        private Config $config,
        private JwtService $jwtService
    ) {}

    public function login(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $usuari = $this->usuariService->getByUsername($data['usuari']);
        if ($usuari === null || !$usuari->checkPassword($data['password'])) {
            // TODO: Llançar un error definit
            $response->getBody()->write(json_encode(["error" => "Usuari no trobat"]));
            return $response->withStatus(401);
        }
        $jwtToken = $this->jwtService->jwtEncode([
            "id" => $usuari->getId(),
            "usuari" => $usuari->getUsuari(),
            "nivell" => $usuari->getNivell(),
            "email" => $usuari->getEmail(),
        ]);
        setcookie(
            name: "token",
            value: $jwtToken,
            httponly: true,
            secure: false,
            domain: $this->config->server["domain"],
            path: $this->config->server["basePath"],
            expires_or_options: time() + (365 * 24 * 60 * 60),
        );
        return $response->withStatus(201);
    }
}
