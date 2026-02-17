<?php

namespace Core;

class Container
{
    protected static ?self $instance = null;

    /** @var array<string, mixed> */
    protected array $bindings = [];

    public static function setInstance(self $container): void
    {
        self::$instance = $container;
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function bind(string $key, callable|object $value): void
    {
        $this->bindings[$key] = $value;
    }

    public function resolve(string $key): mixed
    {
        if (!isset($this->bindings[$key])) {
            return null;
        }
        $value = $this->bindings[$key];
        if (is_callable($value)) {
            return $value($this);
        }
        return $value;
    }

    public function make(string $key): mixed
    {
        return $this->resolve($key);
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->bindings[$key] ?? $default;
    }
}
