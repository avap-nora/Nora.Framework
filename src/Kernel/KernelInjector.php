<?php
/**
 * this file is part of Nora
 */
declare(strict_types=1);

namespace Nora\Framework\Kernel;

use Nora\Architecture\AOP\Compiler\Compiler;
use Nora\Architecture\DI\Annotation\Inject;
use Nora\Architecture\DI\Bind;
use Nora\Architecture\DI\Configuration\AbstractConfigurator;
use Nora\Architecture\DI\Configuration\NullConfigurator;
use Nora\Architecture\DI\Dependency\Dependency;
use Nora\Architecture\DI\Exception\Untargeted;
use Nora\Architecture\DI\Injector;
use Nora\Architecture\DI\InjectorInterface;
use Nora\Architecture\DI\ValueObject\Name;
use Nora\Framework\Kernel\Exception\InvalidContextException;
use Nora\Utility\FileSystem\CreateWritableDirectory;

class KernelInjector implements InjectorInterface
{
    /**
     * @var string
     */
    private $scriptDir;


    /**
     * @var Configurator
     */
    private $configurator;

    /**
     * @var KernelMeta
     */
    private $meta;

    /**
     * @var array
     */
    public $loadedContexts;

    /**
     * Setup Kernel Injector
     */
    public function __construct(KernelMeta $meta)
    {
        $this->meta = $meta;
        $this->scriptDir = $meta->tmpDir . '/di';
        $this->classDir = (new CreateWritableDirectory)($meta->tmpDir . '/class');
        $this->container = $this->getConfigurator()->getContainer();
        $this->container->weaveAspects(new Compiler($this->classDir));
    }

    /**
     * {@inheritDoc}
     */
    public function getInstance($interface, $name = Name::ANY)
    {
        try {
            $instance = $this->container->getInstance($interface, $name);
        } catch (Untargeted $e) {
            $this->bind($interface);
            $instance = $this->getInstance($interface, $name);
        }
        return $instance;
    }

    private function bind(string $class)
    {
        (new Bind($this->container, $class));
        $this->container->getInstance($class, Name::ANY);
        // $bound = $this->container[$class . '-' . Name::ANY];
        // if ($bound instanceof Dependency) {
        //     $this->container->weaveAspect(
        //         new Compiler($this->classDir),
        //         $bound
        //     )->getInstance($class, Name::ANY);
        // }
    }

    /**
     * Configuration
     */
    private function getConfigurator()
    {
        if ($this->configurator instanceof AbstractConfigurator) {
            return $this->configurator;
        }
        $contextsArray = array_reverse(explode('-', $this->meta->context));
        $configurator = new NullConfigurator;

        // Context
        $this->loadedContexts = [];
        foreach ($contextsArray as $contextItem) {
            $class = $this->meta->name . '\Kernel\Context\\' . ucwords($contextItem) . 'Configurator';
            if (! class_exists($class)) {
                $class = 'Nora\Framework\Kernel\Context\\' . ucwords($contextItem) . 'Context';
            }
            if (! is_a($class, AbstractConfigurator::class, true)) {
                throw new InvalidContextException($contextItem);
            }
            $this->loadedContexts[$contextItem] = $class;

            $configurator = is_subclass_of(
                $class,
                AbstractKernelConfigurator::class
            ) ? new $class($this->meta, $configurator) : new $class($configurator);
        }
        if (! $configurator instanceof AbstractConfigurator) {
            throw new InvalidModuleException; // @codeCoverageIgnore
        }

        $configurator->override(new KernelModule($this->meta));

        // Bind
        (new Bind($configurator->getContainer(), InjectorInterface::class))->toInstance($this);

        $this->configurator = $configurator;
        return $configurator;
    }
}
