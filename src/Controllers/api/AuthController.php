<?php

declare(strict_types=1);

namespace Kartalit\Controllers\api;

use Kartalit\Interfaces\AuthServiceInterface;
use Kartalit\Services\UsuariService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthController
{
    public function __construct(
        private UsuariService $usuariService,
        private AuthServiceInterface $authService
    ) {}

    public function login(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $usuari = $this->usuariService->getByUsername($data['usuari']);
        if ($usuari === null || !$this->usuariService->checkPassword($usuari, $data['password'])) {
            // TODO: Llançar un error definit
            $response->getBody()->write(json_encode(["error" => "Error de login"]));
            return $response->withStatus(401);
        }
        $jwtToken = $this->authService->createToken($usuari);
        $this->authService->setCookie($jwtToken);
        return $response->withStatus(201);
    }
}
