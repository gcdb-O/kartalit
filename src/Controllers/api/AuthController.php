<?php

declare(strict_types=1);

namespace Kartalit\Controllers\api;

use Kartalit\Enums\HttpResponseCode;
use Kartalit\Errors\BadRequestException;
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
            throw new BadRequestException("Error de login. Usuari o contrassenya incorrectes.");
        }
        $jwtToken = $this->authService->createToken($usuari, 3600);
        $this->authService->setCookie($jwtToken);
        return $response->withStatus(HttpResponseCode::NO_CONTENT->value);
    }
}
