<?php
declare(strict_types=1);

namespace CommunityHub\Components\Factory;

use ReflectionMethod;
use Throwable;

use function call_user_func;
use function method_exists;
use function class_exists;
use function preg_match;
use function get_class;
use function is_object;
use function sprintf;

final class Factory
{
    private array $singletons = [];

    private string $method;

    /**
     * @throws Exception
     *     if the make method name is invalid.
     */
    public static function make(string $method = 'make'): self
    {
        return new self($method);
    }

    /**
     * @throws Exception
     *     if the make method name is invalid.
     */
    public function __construct(string $method = 'make')
    {
        if (!preg_match('~[a-zA-Z][a-zA-Z0-9]~', $method)) {
            throw $this->createException(
                'Invalid method name: %s.',
                [$method]
            );
        }

        $this->method = $method;
    }

    /**
     * @throws Exception
     *     if there was an error calling the factory method.
     * @throws Throwable
     *     from inside the factory method up the stack.
     */
    public function __invoke(string $classPath): mixed
    {
        if (!isset($this->singletons[$classPath])) {
            $this->singletons[$classPath] = $this->makeSingleton($classPath);
        }

        return $this->singletons[$classPath];
    }

    /**
     * @throws Exception
     *     if there was an error calling the factory method.
     * @throws Throwable
     *     from inside the factory method up the stack.
     */
    private function makeSingleton(string $classPath): mixed
    {
        try {
            $result = call_user_func([$classPath, $this->method], $this);
        } catch (Throwable $e) {
            if (!class_exists($classPath)) {
                throw $this->createException(
                    'Class does not exist: %s.',
                    [$classPath]
                );
            }

            if (!method_exists($classPath, $this->method)) {
                throw $this->createException(
                    'Method does not exist: %s::%s().',
                    [$classPath, $this->method]
                );
            }

            $isStatic = (new ReflectionMethod($classPath, $this->method))->isStatic();

            if (!$isStatic) {
                throw $this->createException(
                    'Method is not static: %s::%s().',
                    [$classPath, $this->method]
                );
            }

            throw $e;
        }

        if (!is_object($result) || (get_class($result) !== $classPath)) {
            throw $this->createException(
                'Method did not return an instance of self: %s::%s().',
                [$classPath, $this->method]
            );
        }

        return $result;
    }

    private function createException(string $message, array $parameters): Exception
    {
        require __DIR__ . '/Exception.php';

        return new Exception(sprintf($message, ...$parameters));
    }
}
