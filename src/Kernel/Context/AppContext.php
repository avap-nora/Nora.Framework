<?php
namespace Nora\Framework\Kernel\Context;

use Nora\Framework\Kernel\AbstractKernelConfigurator;
use Nora\Framework\Plugins\Cache\Adapter\PhpCache\PhpCacheConfigurator;
use Nora\Framework\Plugins\Globals\GlobalsConfigurator;
use Nora\Framework\Plugins\Logger\Configurator\MonologConfigurator;

class AppContext extends AbstractKernelConfigurator
{
    public function configure()
    {
        // $this->install(new GlobalsConfigurator());
        // $this->install(new MonologConfigurator($this->meta));
        // $this->install(new PhpCacheConfigurator($this->meta));
        // $this->install(new GuzzleHttpConfigurator());
    }
}
