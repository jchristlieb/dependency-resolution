# Building a dependency-resolution IoC Container

An attempt to understand Laravel's implementation of the automated `dependency resolution`. By replicating this functionality based on the tutorial from [Dayle Rees](https://daylerees.com/container-baking/).

## The idea

An dependency resolution inversion of control container manages the creation of services in an application. Thereby it creates services just in the moment of request. In contrast, to creating all available services when they are registered, i.e start of the application. 

## Features of the IoC container

We are going to build the features of the dependency resolution IoC container step by step:

#### A. Build a container that stores and retrieves services 
1. build a container class
2. add an `instance()` function that stores services 
3. add a `make()` function that retrieves services 

#### B. Add inversion of control functionality 
1. add a `bind()` function that accepts `closures` to bind services 
2. update the `make()` function that it retrieves `instances` and `bindings`
3. cache services as an `instances` once requested (singelton-concept)

#### C. Add automated (recursive) dependency resolution
7. update the `make()` function with a `class_exists()` functionality
8. refactor `bind()` function by extracting the dependency resolution into a 
    `autoResolve()` function
9. add recursive dependency resolution functionality  


## Concepts 

This tutorial makes use of the following concepts. 

#### 1. Inversion of control

Services are created when they are needed. In contrast to when they are registered. 

#### 2. Dependency resolution 

It is about automated creation of classes during the run time of an application. 

* Classes without constructor parameter (dependencies) can be created automatically.
* Classes that need as constructor parameter another class or even multiple classes,  can only be instantiated when this dependency is available. The concept of `recursive dependency resolution`
    tries to instantiate those dependency classes. 
* Classes that need `integer` constructor parameters can not be instantiated through 
    dependency resolution.      

#### 3. PHP reflection classes 

PHP has a [reflection API](http://php.net/manual/en/book.reflection.php) that enable us to reverse engineer a class and retrieve information about the nature of the class and associated interfaces, functions, extensions. 

To automatically resolve classes wit construction parameters we need to make use of the reflection API to receive the dependent class instantiate them accordingly. 



## Testing

You will find withing the `tests` directory a  `containerTest.php` with 6 different test cases. Run all tests with -> `./vendor/bin/phpunit` . 

