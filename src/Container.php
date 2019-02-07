<?php
/**
 * Created by PhpStorm.
 * User: jan
 * Date: 27.12.18
 * Time: 13:29
 */

namespace App;

use mysql_xdevapi\Exception;

class Container
{

    protected $instances = [];
    protected $bindings = [];

    // add an instance
    public function instance($key, $value)
    {
        $this->instances[$key] = $value;

        return $this;
    }

    //
    public function bind($key, $value)
    {
        $this->bindings[$key] = $value;

        return $this;
    }

    //retrieve instance
    public function make($key)
    {
        if (array_key_exists($key, $this->instances)) {
            return $this->instances[$key];
        }

        if (array_key_exists($key, $this->bindings)) {
            $resolver = $this->bindings[$key];
            return $this->instances[$key] = $resolver();
        }

        if ($instance = $this->autoResolve($key)) {
            return $instance;
        }

        throw new \Exception('Unable to resolve the requested instance');
    }

    //try to find and instantiate class outside
    // of bind and instance context
    public function autoResolve($key)
    {
        // if class named in $key doesn't exist return false
        if (!class_exists($key)) {
            return false;
        }

        // make a Reflection class to request information about the required class
        $reflectionClass = new \ReflectionClass($key);

        // if class is not instantiable return false
        if (!$reflectionClass->isInstantiable()) {
            return false;
        }

        // if class has no constructor we can consider it to be a simple class
        // and just make an instance of it. Nice result :-)
        if (!$constructor = $reflectionClass->getConstructor()) {
            return new $key;
        }

        // if class has a constructor, lets retrieve the parameters
        $params = $constructor->getParameters();
        // create a buffer that will be used to save the parameters into
        $args = [];

        // lets try to resolve the parameters of the constructor
        try {
            foreach ($params as $param) {
                // try to get class name for each parameter
                $paramClass = $param->getClass()->getName();
                // save it into $args array
                $args[] = $this->make($paramClass);
            }

        } catch (\Exception $e) {
            // if class name can not be resolved we can assume to deal with a
            // complex class and we are unable to instantiate it
            throw new \Exception('Unable to resolve complex dependencies');
        }

        // if no exception was thrown, we may assume that all constructor parameters are
        // resolved and we are able to create an instance of the requested class
        return $reflectionClass->newInstanceArgs($args);

    }
}