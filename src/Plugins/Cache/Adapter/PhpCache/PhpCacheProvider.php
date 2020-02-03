<?php
namespace Nora\Framework\Plugins\Cache\Adapter\PhpCache;

use Cache\Adapter\Common\AbstractCachePool;
use Cache\Bridge\SimpleCache\SimpleCacheBridge;
use Cache\Namespaced\NamespacedCachePool;
use Cache\Prefixed\PrefixedCachePool;
use Nora\Architecture\DI\Annotation\Named;
use Nora\Architecture\DI\Dependency\ProviderInterface;
use Nora\Architecture\DI\Injector\InjectionPoint;
use Nora\Architecture\DI\Injector\InjectionPointInterface;
use Nora\Architecture\DI\Configuration\AbstractConfigurator;
use Nora\Framework\Kernel\KernelMeta;
use ReflectionClass;

class PhpCacheProvider implements ProviderInterface
{
    private $ip;
    private $meta;

    /**
     */
    public function __construct(
        InjectionPointInterface $ip,
        AbstractCachePool $pool
    ) {
        $this->ip = $ip;
        $this->name = str_replace(
            '\\',
            '_',
            $ip->getClass()->name
        );
        $this->pool = $pool;
    }

    public function get()
    {
        return new SimpleCacheBridge(
            new PrefixedCachePool($this->pool, $this->name.'-')
        );
    }
}
