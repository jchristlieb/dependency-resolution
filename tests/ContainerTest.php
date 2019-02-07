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

    /** @test */
    public function resolve_class_instance_by_name_without_binding(){
        // create new container instance and do not bind anything into it
        $container = new \App\Container();
        // create a new dummy instance through make() function
        $dummy = $container->make(DummyClass::class);
        // check if $dummy is indeed an instance of DummyClass
        $this->assertInstanceOf('DummyClass', $dummy);
    }

    /** @test */
    public function resolve_dependencies_of_dependencies(){
        $container = new \App\Container();
        $baz = $container->make(Baz::class);
        $this->assertInstanceOf('Baz', $baz);
    }
}

class DummyClass{}
class Foo {}
class Bar { function __construct(Foo $foo) {} }
class Baz { function __construct(Bar $bar) {} }
