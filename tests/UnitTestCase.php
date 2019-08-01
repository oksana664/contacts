<?php

use Phalcon\Di;
use Phalcon\Di\InjectionAwareInterface;
use Phalcon\Test\Traits\UnitTestCase as UnitTestCaseTrait;
use PHPUnit\Framework\TestCase as TestCase;

abstract class UnitTestCase extends TestCase implements InjectionAwareInterface
{
    use UnitTestCaseTrait;

    /**
     * @var bool
     */
    private $_loaded = false;

    public function setUp(): void
    {
        parent::setUp();
        $this->setUpPhalcon();

        // Load any additional services that might be required during testing
        $di = Di::getDefault();

        // Get any DI components here. If you have a config, be sure to pass it to the parent

        $this->setDi($di);

        $this->_loaded = true;
    }

    protected function tearDown(): void
    {
        $this->tearDownPhalcon();

        parent::tearDown();
    }

    /**
     * Check if the test case is setup properly
     *
     * @throws \PHPUnit_Framework_IncompleteTestError;
     */
    public function __destruct()
    {
        if (!$this->_loaded) {
            throw new \PHPUnit_Framework_IncompleteTestError(
                "Please run parent::setUp()."
            );
        }
    }
}