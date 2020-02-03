<?php
namespace Nora\Framework\Plugins\Globals;

use Nora\Architecture\DI\Dependency\ProviderInterface;
use Nora\Utility\DotEnv\EnvLoader;
use Nora\Architecture\DI\Annotation\Named;
use Nora\Architecture\DI\Annotation\Inject;
use Nora\Utility\Globals\Globals;


class GlobalsProvider implements ProviderInterface
{
    private $server;
    private $get;
    private $post;
    private EnvLoader $env;

    /**
     * @Named("server=_SERVER, get=_GET, post=_POST")
     * @Inject
     */
    public function __construct($server, $get, $post, EnvLoader $env = null)
    {
        if ($env instanceof EnvLoader) {
            $env->override();
        }
        $this->server = $server;
        $this->get = $get;
        $this->post = $post;
        $this->env = $env;

    }

    public function get()
    {
        return new Globals(
            $this->server,
            $this->get,
            $this->post,
            $this->env
        );
    }
}
