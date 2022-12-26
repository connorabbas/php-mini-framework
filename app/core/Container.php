<?php

namespace App\Core;

use Exception;
use ReflectionClass;
use ReflectionParameter;
use Psr\Container\ContainerInterface;
use ReflectionNamedType;
use ReflectionUnionType;

class Container implements ContainerInterface
{
    private array $entries = [];

    public function get(string $id)
    {
        if ($this->has($id)) {
            $entry = $this->entries[$id];
            return $entry($this);
        }

        return $this->resolve($id);
    }

    public function set(string $id, callable $callback)
    {
        $this->entries[$id] = $callback;
    }

    public function has(string $id): bool
    {
        return isset($this->entries[$id]);
    }

    public function resolve(string $id)
    {
        $reflectionClass = new ReflectionClass($id);
        if (!$reflectionClass->isInstantiable()) {
            throw new Exception('The provided class: "' . $id . '" is not instantiable.');
        }

        $constructor = $reflectionClass->getConstructor();
        if (!$constructor) {
            return new $id();
        }

        $parameters = $constructor->getParameters();
        if (!$parameters) {
            return new $id();
        }

        $dependencies = array_map(
            function (ReflectionParameter $parameter) use ($id) {
                $name = $parameter->getName();
                $type = $parameter->getType();

                if (!$type) {
                    throw new Exception(
                        'Failed to resolve class: "' . $id . '" because "' . $name . '"' .
                        ' is missing a type hint in the constructor.'
                    );
                }

                if ($type instanceof ReflectionUnionType) {
                    throw new Exception(
                        'Failed to resolve class: "' . $id . '" because param: "' . $name . '"' .
                        ' is a union type.'
                    );
                }

                if ($type instanceof ReflectionNamedType && !$type->isBuiltin()) {
                    return $this->get($type->getName());
                }

                throw new Exception(
                    'Failed to resolve class: "' . $id . '" because invalid param: "' . $name . '"'
                );
            },
            $parameters
        );

        return $reflectionClass->newInstanceArgs($dependencies);
    }
}
