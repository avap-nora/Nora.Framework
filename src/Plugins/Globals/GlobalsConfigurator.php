<?php
namespace Nora\Framework\Plugins\Globals;

use Nora\Architecture\DI\Configuration\AbstractConfigurator;
use Nora\Utility\DotEnv\EnvLoader;
use Nora\Utility\Globals\Globals;

class GlobalsConfigurator extends AbstractConfigurator
{
    public function configure()
    {
        // グローバル変数をキャプチャする
        $this
            ->bind()
            ->annotatedWith('_SERVER')
            ->toInstance($_SERVER);
        $this
            ->bind()
            ->annotatedWith('_GET')
            ->toInstance($_GET);
        $this
            ->bind()
            ->annotatedWith('_POST')
            ->toInstance($_POST);
        $this
            ->bind(Globals::class)
            ->toProvider(GlobalsProvider::class);
    }
}
