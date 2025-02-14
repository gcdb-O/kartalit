<?php

declare(strict_types=1);

namespace Kartalit\Config;

readonly class Config
{
    protected array $config;

    public function __construct(array $env)
    {
        $this->config = [
            "db" => [
                'host' => $env["ENV_DB_HOST"],
                'user' => $env["ENV_DB_USER"],
                'password' => $env["ENV_DB_PSWD"] ?? null,
                'dbname' => $env["ENV_DB_NAME"],
                'driver' => $env['ENV_DB_DRIVER'] ?? 'pdo_mysql',
            ],
            "jwt" => [
                'secret' => $env["ENV_JWT_SECRET"]
            ],
            'server' => [
                'isProd' => ($env["ENV_SERVER_PROD"] === "true") ? true : false,
                'domain' => $env["ENV_SERVER_DOMAIN"] ?? 'localhost',
                'basePath' => $env["ENV_SERVER_BASEPATH"] ?? '',
                'salt' => $env["ENV_SERVER_SALT"]
            ]
        ];
    }

    public function __get(string $name): array|null
    {
        return $this->config[$name] ?? null;
    }
}
