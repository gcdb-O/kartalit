<?php

declare(strict_types=1);

namespace Kartalit\Services;

use Kartalit\Config\Config;
use Kartalit\Interfaces\CookieServiceInterface;
use Kartalit\Interfaces\SessionServiceInterface;

class SessionService implements SessionServiceInterface
{
    private string $sessionName = "KartalitSession";
    public function __construct(
        private Config $config,
        private CookieServiceInterface $cookieService,
    ) {}
    public function startSession(): void
    {
        session_set_cookie_params([
            "domain" => $this->config->server["domain"],
            "path" => $this->config->server["basePath"]
        ]);
        session_name($this->sessionName);
        session_start();
    }
    public function endSession(): void
    {
        $this->cookieService->deleteCookie($this->sessionName);
        session_destroy();
    }
}
