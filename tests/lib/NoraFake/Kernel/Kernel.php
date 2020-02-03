<?php
namespace NoraFake\Kernel;

use Nora\Architecture\DI\InjectorInterface;
use Nora\Framework\Kernel\KernelInterface;
use Nora\Framework\Kernel\KernelMeta;
use Nora\Framework\Plugins\Logger\LoggerTrait;
use Nora\Utility\Globals\Globals;
use Psr\SimpleCache\CacheInterface;

class Kernel implements KernelInterface
{
    public $injector;
    public $globals;
    public $cache;
    public $meta;

    use LoggerTrait;

    public function __construct(
        InjectorInterface $injector,
        CacheInterface $cache,
        Globals $globals,
        KernelMeta $meta
    ) {
        $this->injector = $injector;
        $this->globals = $globals;
        $this->cache = $cache;
        $this->meta = $meta;
    }
}
