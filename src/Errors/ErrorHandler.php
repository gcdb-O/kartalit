<?php

declare(strict_types=1);

namespace Kartalit\Errors;

use Kartalit\Config\Config;
use Kartalit\Enums\ApiResponseStatus;
use Kartalit\Enums\Cookie;
use Kartalit\Enums\HttpStatusCode;
use Kartalit\Interfaces\AuthServiceInterface;
use Kartalit\Interfaces\RenderServiceInterface;
use Kartalit\Schemas\ApiResponse;
use Kartalit\Schemas\ExceptionDisplayDetails;
use Kartalit\Schemas\RenderContext;
use Kartalit\Services\ApiResponseService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpNotFoundException;
use Slim\Interfaces\ErrorHandlerInterface;
use Slim\Psr7\Response;
use Throwable;
use TypeError;

class ErrorHandler implements ErrorHandlerInterface
{
    private bool $displayErrorDetails = false;
    public function __construct(
        private Config $config,
        private RenderServiceInterface $renderService,
        private ApiResponseService $apiResponseService,
        private AuthServiceInterface $authService,
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
            return $this->handleWebError($request, $throwable);
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
        switch (get_class($throwable)) {
            case InvalidTypeException::class:
                /** @var InvalidTypeException $throwable */
                $data = $apiResponse->getData();
                $data["fields"] = $throwable->getFields();
                $apiResponse->setData($data);
                break;
            case TypeError::class:
                $response = $response->withStatus(HttpStatusCode::BAD_REQUEST->value);
                break;
            default:
                $response = $response->withStatus(HttpStatusCode::SERVER_ERROR->value);
                break;
        }

        $response = $this->apiResponseService->toJson($response, $apiResponse, $throwable->getCode());
        $response = $response->withHeader("Content-Type", "application/json");
        return $response;
    }
    private function handleWebError(Request $request, Throwable $throwable): ResponseInterface
    {
        $response = new Response();
        $renderContextData = [
            "code" => HttpStatusCode::SERVER_ERROR->value,
            "message" => "Alguna cosa ha fallat.",
            "displayErrorDetails" => $this->displayErrorDetails,
            "error" => ExceptionDisplayDetails::toArray($throwable),
        ];
        switch (get_class($throwable)) {
            case ExpiredTokenException::class:
                $this->authService->deleteCookie(Cookie::AUTH->value);
                return $response
                    ->withStatus(HttpStatusCode::REDIRECT_TEMP->value)
                    ->withHeader("Location", $this->config->server["basePath"]);
            case UnauthorizedException::class:
            case InvalidTokenException::class:
                return $response
                    ->withStatus(HttpStatusCode::REDIRECT_TEMP->value)
                    ->withHeader("Location", $this->config->server["basePath"] . "/login");
            case HttpNotFoundException::class:
                $renderContextData["code"] = HttpStatusCode::NOT_FOUND->value;
                $renderContextData['message'] = "Pàgina no trobada";
                $response = $response->withStatus(HttpStatusCode::NOT_FOUND->value);
                break;
            case EntityNotFoundException::class:
                $renderContextData["code"] = $throwable->getCode();
                $renderContextData['message'] = $throwable->getMessage();
                $response = $response->withStatus($throwable->getCode());
                break;
            default:
                $response = $response->withStatus(HttpStatusCode::SERVER_ERROR->value);
                break;
        }
        $renderContext = new RenderContext($request, "Error", $renderContextData);
        return $this->renderService->render($response, "Pages/notFound.html.twig", $renderContext);
    }
}
