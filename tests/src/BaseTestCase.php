<?php

namespace Tests;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Symfony\Component\HttpKernel\KernelInterface;

use TestApp\TestAppBundle;
use Troytft\DataMapperBundle\DataMapperBundle;

abstract class BaseTestCase extends \Nyholm\BundleTest\BaseBundleTestCase
{
    /**
     * @var KernelInterface
     */
    protected $kernel;

    public function __construct()
    {
        parent::__construct();

        $this->bootKernel();
    }

    protected function getBundleClass()
    {
        return TestAppBundle::class;
    }

    protected function createKernel()
    {
        $this->kernel = parent::createKernel();
        $this->kernel->setRootDir(__DIR__ . '/../test-app');
        $this->kernel->addBundle(DataMapperBundle::class);
        $this->kernel->addBundle(DoctrineBundle::class);
        $this->kernel->addConfigFile(__DIR__ . '/../test-app/Resources/config/config.yml');

        return $this->kernel;
    }

    protected function getKernel(): KernelInterface
    {
        return $this->kernel;
    }
}
