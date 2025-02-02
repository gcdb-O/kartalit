<?php

declare(strict_types=1);

namespace Kartalit\Errors;

use Kartalit\Config\Config;
use Kartalit\Services\TwigContext;
use Kartalit\Services\TwigService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use Slim\Interfaces\ErrorHandlerInterface;
use Slim\Psr7\Response;
use Throwable;

class ErrorHandler implements ErrorHandlerInterface
{
    public function __construct(
        private Config $config,
        private TwigService $twig,
        protected ?LoggerInterface $logger = null,
    ) {}
    public function __invoke(
        Request $request,
        Throwable $throwable,
        bool $displayErrorDetails,
        bool $logErrors,
        bool $logErrorDetails
    ): Response {
        //TODO: Gestionar si $logErrors
        if ($logErrors && $this->logger !== null) {
            $this->logger->error($throwable->getMessage());
        }
        $route = $request->getUri()->getPath();
        $acceptHeader = explode(",", $request->getHeaderLine("Accept")) ?? [];
        if (
            strpos($route, $this->config->server["basePath"] . "/api") === 0 &&
            in_array("application/json", $acceptHeader)
        ) {
            return $this->handleApiError($throwable);
        } else {
            return $this->handleWebError($request);
        }
    }
    private function handleApiError(Throwable $throwable): ResponseInterface
    {
        $response = new Response();
        $response->getBody()->write(json_encode([
            "message" => $throwable->getMessage(),
            "exception" => [
                "type" => get_class($throwable),
                "code" => $throwable->getCode(),
            ]
        ]));

        $response = $response->withHeader("Content-Type", "application/json");

        return $response->withStatus($throwable->getCode());
    }
    private function handleWebError(Request $request): ResponseInterface
    {
        $response = new Response();
        $response->withStatus(404);
        return $this->twig->render($response, "notFound.html.twig", new TwigContext($request, "Pàgina no trobada"));
    }
    private function handleNotFoundException(NotFoundException $throwable): ResponseInterface
    {
        $response = new Response();
        $response->getBody()->write(json_encode([
            "message" => $throwable->getMessage(),
            "exception" => [
                "type" => get_class($throwable),
                "code" => $throwable->getCode(),
            ]
        ]));
        return $response->withStatus($throwable->getCode());
    }
}
