<?php
declare(strict_types=1);

namespace Tests;

use CommunityHub\Components\Factory\Exception;
use CommunityHub\Components\Factory\Factory;

use TestClasses\SingletonThatDoesNotReturnAnInstance;
use TestClasses\SingletonThatReturnsTheWrongInstance;
use TestClasses\SingletonWithErroneousMakeMethod;
use TestClasses\SingletonWithNonStandardMethod;
use TestClasses\SingletonWithNonStaticMethod;
use TestClasses\SingletonInsideSingleton;
use TestClasses\Singleton;

use PHPUnit\Framework\TestCase;

use InvalidArgumentException;

class FactoryTests extends TestCase
{
    private Factory $factory;

    protected function setUp(): void
    {
        $this->factory = new Factory;
    }

    /**
     * @test
     */
    public function it_should_create_a_singleton(): void
    {
        $result = ($this->factory)(Singleton::class);

        $this->assertInstanceOf(Singleton::class, $result);
        $this->assertSame(123, $result->getNum());
    }

    /**
     * @test
     */
    public function it_should_not_recreate_a_singleton_once_already_created_once(): void
    {
        $result1 = ($this->factory)(Singleton::class);
        $result2 = ($this->factory)(Singleton::class);

        $this->assertInstanceOf(Singleton::class, $result1);
        $this->assertInstanceOf(Singleton::class, $result2);

        $this->assertSame($result1, $result2);
    }

    /**
     * @test
     */
    public function it_should_create_a_singleton_within_another_singleton(): void
    {
        $result = ($this->factory)(SingletonInsideSingleton::class);

        $this->assertInstanceOf(SingletonInsideSingleton::class, $result);
        $this->assertInstanceOf(Singleton::class, $result->getSingleton());
    }

    /**
     * @test
     */
    public function it_should_not_recreate_a_singleton_within_another_singleton(): void
    {
        $result1 = ($this->factory)(Singleton::class);
        $result2 = ($this->factory)(SingletonInsideSingleton::class);

        $this->assertSame($result1, $result2->getSingleton());
    }

    /**
     * @test
     */
    public function it_should_create_a_singleton_with_a_non_standard_create_method(): void
    {
        $factory = new Factory('create');

        $result = $factory(SingletonWithNonStandardMethod::class);

        $this->assertInstanceOf(SingletonWithNonStandardMethod::class, $result);
        $this->assertSame(123, $result->getNum());
    }

    /**
     * @test
     */
    public function it_should_throw_a_factory_method_throwable(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Some error.');

        ($this->factory)(SingletonWithErroneousMakeMethod::class);
    }

    /**
     * @test
     */
    public function it_should_fail_if_the_class_does_not_exist(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(
            'Class does not exist: TestClasses\\SingletonThatDoesNotExist.'
        );

        ($this->factory)('TestClasses\\SingletonThatDoesNotExist');
    }

    /**
     * @test
     */
    public function it_should_fail_if_the_factory_method_does_not_exist(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(
            'Method does not exist: TestClasses\\SingletonWithNonStandardMethod::make().'
        );

        ($this->factory)(SingletonWithNonStandardMethod::class);
    }

    /**
     * @test
     */
    public function it_should_fail_if_the_factory_method_is_not_static(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(
            'Method is not static: TestClasses\\SingletonWithNonStaticMethod::make().'
        );

        ($this->factory)(SingletonWithNonStaticMethod::class);
    }

    /**
     * @test
     */
    public function it_should_fail_if_the_factory_method_does_not_return_an_instance(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(
            'Method did not return an instance of self: '
                . 'TestClasses\\SingletonThatDoesNotReturnAnInstance::make().'
        );

        ($this->factory)(SingletonThatDoesNotReturnAnInstance::class);
    }

    /**
     * @test
     */
    public function it_should_fail_if_the_singleton_returns_the_wrong_instance(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(
            'Method did not return an instance of self: '
            . 'TestClasses\\SingletonThatReturnsTheWrongInstance::make().'
        );

        ($this->factory)(SingletonThatReturnsTheWrongInstance::class);
    }

    /**
     * @test
     */
    public function it_should_fail_if_the_method_name_is_invalid(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid method name: 1.');

        new Factory('1');
    }
}
