<?php

declare(strict_types=1);

namespace App\Listener;

use Hyperf\Di\Annotation\Inject;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Framework\Event\AfterWorkerStart;
use Hyperf\Snowflake\MetaGeneratorInterface;
use Psr\Container\ContainerInterface;
use Hyperf\Event\Contract\ListenerInterface;

/**
 * @Listener
 */
class InitSnowflakeListener implements ListenerInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @Inject()
     * @var MetaGeneratorInterface
     */
    protected $generator;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function listen(): array
    {
        return [
            AfterWorkerStart::class
        ];
    }

    public function process(object $event)
    {
        $this->generator->generate();
    }
}
