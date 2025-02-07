<?php

declare(strict_types=1);

namespace Kartalit\Errors;

use Kartalit\Config\Config;
use Kartalit\Enums\ApiResponseStatus;
use Kartalit\Enums\HttpStatusCode;
use Kartalit\Schemas\ApiResponse;
use Kartalit\Schemas\TwigContext;
use Kartalit\Services\ApiResponseService;
use Kartalit\Services\TwigService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use Slim\Interfaces\ErrorHandlerInterface;
use Slim\Psr7\Response;
use Throwable;

class ErrorHandler implements ErrorHandlerInterface
{
    private bool $displayErrorDetails = false;
    public function __construct(
        private Config $config,
        private TwigService $twig,
        private ApiResponseService $apiResponseService,
        protected ?LoggerInterface $logger = null,
    ) {}
    public function __invoke(
        Request $request,
        Throwable $throwable,
        bool $displayErrorDetails,
        bool $logErrors,
        bool $logErrorDetails
    ): Response {
        $this->displayErrorDetails = $displayErrorDetails;
        //TODO: Gestionar si $logErrors
        if ($logErrors && $this->logger !== null) {
            $this->logger->error($throwable->getMessage());
        }
        $route = $request->getUri()->getPath();
        $acceptHeader = explode(",", $request->getHeaderLine("Accept")) ?? [];
        if (
            (strpos($route, $this->config->server["basePath"] . "/api") === 0 &&
                in_array("application/json", $acceptHeader)) ||
            $acceptHeader === ["*/*"]
        ) {
            return $this->handleApiError($throwable);
        } else {
            return $this->handleWebError($request);
        }
    }
    private function handleApiError(Throwable $throwable): ResponseInterface
    {
        $response = new Response();
        $apiResponse = new ApiResponse(
            message: $throwable->getMessage(),
            status: ApiResponseStatus::ERROR,
        );
        if ($this->displayErrorDetails) {
            $apiResponse->setExcepion($throwable);
        }

        $response = $this->apiResponseService->toJson($response, $apiResponse, $throwable->getCode());
        $response = $response->withHeader("Content-Type", "application/json");
        return $response;
    }
    private function handleWebError(Request $request): ResponseInterface
    {
        //TODO: Separar els 404 dels 401, 403, 500 i mostrar error per consola i noProd
        $response = new Response();
        $response->withStatus(HttpStatusCode::NOT_FOUND->value);
        return $this->twig->render($response, "Pages/notFound.html.twig", new TwigContext($request, "Pàgina no trobada"));
    }
}
