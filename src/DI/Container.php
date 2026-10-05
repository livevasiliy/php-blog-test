<?php

declare(strict_types=1);

namespace App\DI;

use ReflectionClass;
use RuntimeException;

final class Container
{
    private array $bindings = [];
    private array $instances = [];

    public function singleton(string $id, object|callable $factory): void
    {
        $this->bindings[$id] = $factory;
    }

    public function get(string $id): mixed
    {
        if (array_key_exists($id, $this->instances)) {
            return $this->instances[$id];
        }
        $factory = $this->bindings[$id] ?? $id;
        $instance = is_callable($factory) ? $factory($this) : $this->autowire($factory);
        return $this->instances[$id] = $instance;
    }

    public function has(string $id): bool
    {
        return array_key_exists($id, $this->bindings) || class_exists($id);
    }

    public function autowire(string $class): object
    {
        $reflection = new ReflectionClass($class);
        $constructor = $reflection->getConstructor();
        if (!$constructor) {
            return $reflection->newInstance();
        }
        $arguments = [];
        foreach ($constructor->getParameters() as $parameter) {
            $type = $parameter->getType();
            if (!$type || $type->isBuiltin()) {
                if ($parameter->isDefaultValueAvailable()) {
                    $arguments[] = $parameter->getDefaultValue();
                    continue;
                }
                throw new RuntimeException("Cannot resolve {$class}::{$parameter->getName()}");
            }
            $arguments[] = $this->get($type->getName());
        }
        return $reflection->newInstanceArgs($arguments);
    }
}
