<?php

declare(strict_types = 1);

namespace PHPDocToolTest;

use Auryn\Injector;

trait Auryn
{
    /** @var Injector */
    private $injector;

    protected function setUp()
    {
        parent::setUp();

        $this->injector = createInjector();
    }

//    protected function tearDown()
//    {
//        parent::tearDown();
//    }

    public function make(string $classname)
    {
        return $this->injector->make($classname);
    }
}