<?php
/**
 * Created by PhpStorm.
 * User: jan
 * Date: 27.12.18
 * Time: 13:17
 */



class ContainerTest extends \PHPUnit\Framework\TestCase
{

    /** @test */
    public function it_can_instantiated()
    {
        $container = new \App\Container;
        $this->assertInstanceOf(\App\Container::class, $container);
    }

    /** @test */
    public function instance_can_be_bound_and_resolved_from_the_container()
    {
        $dummy = new DummyClass();
        $container = new \App\Container();
        $container->instance(DummyClass::class, $dummy);
        $this->assertSame($dummy, $container->make(DummyClass::class));
    }

    /** @test */
    public function exception_is_thrown_when_instance_is_not_found()
    {
        $this->expectException(\Exception::class);
        $container = new \App\Container();
        $container->make('FakeClass');
    }

    /** @test */
    public function singleton_bindings_can_be_resolved()
    {
        $resolver = function () { return new DummyClass(); };
        $container = new \App\Container();
        $container->bind(DummyClass::class, $resolver);
        $this->assertInstanceOf('DummyClass', $container->make(DummyClass::class));
        $dummy = $container->make(DummyClass::class);
        $this->assertSame($dummy, $container->make(DummyClass::class));
    }
}

class DummyClass{};