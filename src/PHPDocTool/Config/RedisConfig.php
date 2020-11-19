<?php

declare(strict_types=1);

namespace PHPDocTool\Config;

class RedisConfig
{

    /** @var string */
    private $host;

    /** @var string */
    private $password;


    /** @var int */
    private $port;

    /**
     *
     * @param string $host
     * @param string $password
     * @param int $port
     */
    public function __construct(string $host, string $password, int $port)
    {
        $this->host = $host;
        $this->password = $password;
        $this->port = $port;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getPort(): int
    {
        return $this->port;
    }
}
