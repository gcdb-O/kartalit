<?php

declare(strict_types=1);

namespace Kartalit\Middlewares;

use Middlewares\DeflateEncoder;
use Middlewares\GzipEncoder;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class EncodeContent implements MiddlewareInterface
{
    private static $maxSize = 1024;
    public function __construct(private GzipEncoder $gzipEncoder, private DeflateEncoder $deflateEncoder) {}
    public function process(Request $request, RequestHandler $handler): Response
    {
        $response = $handler->handle($request);

        $size = $response->getBody()->getSize();
        if ($size > $this::$maxSize) {
            // TODO: Arreglar això
            $response = $this->deflateEncoder->process($request, $handler);
            $response = $this->gzipEncoder->process($request, $handler);
        }
        return $response;
    }
}
