<?php
/**
 * this file is part of Nora
 */
declare(strict_types=1);

namespace Nora\Framework\Kernel;

use Nora\Utility\DotEnv\LoadDotEnvFile;

class KernelMeta
{
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $context;
    /**
     * @var string
     */
    public $directory;
    /**
     * @var string
     */
    public $tmpDir;
    /**
     * @var string
     */
    public $logDir;

    public function __construct(string $name, string $context, string $directory)
    {
        $this->name = $name;
        $this->context = $context;
        $this->directory = $directory;

        (new LoadDotEnvFile)($this->directory);
    }
}
