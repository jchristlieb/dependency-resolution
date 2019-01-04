<?php
/**
 * Created by PhpStorm.
 * User: jan
 * Date: 27.12.18
 * Time: 13:29
 */

namespace App;

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

        throw new \Exception('Unable to resolve the requested instance');
    }


}