<?php

namespace Aslam\Bca\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public function setUP(): void
    {
        parent::setUp();
    }
}
