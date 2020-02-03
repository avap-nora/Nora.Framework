<?php
namespace Nora\Framework\DI;

use NoraFake\FakeCollection;
use NoraFake\FakeComponent;
use NoraFake\FakeComponent2;
use NoraFake\FakeConfigurator;
use NoraFake\FakeDatetime;
use NoraFake\FakeDatetimeInterface;
use NoraFake\FakeDatetimeProvider;
use NoraFake\FakeLang;
use NoraFake\FakeMainConfigurator;
use NoraFake\FakeMessage;
use NoraFake\FakeMessageInterface;
use NoraFake\FakeMyName;
use NoraFake\FakeSubConfigurator;
use NoraFake\FakeTrace;
use NoraFake\FakeTraceClient;
use NoraFake\FakeTraceInterceptor;
use Nora\Framework\AOP\Compiler\Compiler;
use Nora\Framework\AOP\Compiler\SpyCompiler;
use Nora\Framework\AOP\Pointcut\Matcher;
use Nora\Framework\AOP\Pointcut\Pointcut;
use Nora\Framework\DI\Configuration\AbstractConfigurator;
use Nora\Framework\DI\Container\ContainerInterface;
use Nora\Framework\DI\Dependency\Dependency;
use Nora\Framework\DI\Exception\Untargeted;
use Nora\Framework\DI\Injector\InjectionPoints;
use Nora\Framework\DI\ValueObject\Scope;
use PHPUnit\Framework\TestCase;

class ConfiguratorTest extends TestCase
{
    /**
     * @test
     */
    public function コンフィギュレーション()
    {
        $configurator = new FakeConfigurator();
        $injector = unserialize(
            serialize(
                new Injector($configurator)
            )
        );
        $comp = $injector->getInstance(FakeTraceClient::class);
        $this->assertEquals('(trace) aaa hajime bbb', $comp->intercepted());
    }

    /**
     * @test
     */
    public function コンフィギュレーションネスト()
    {
        $configurator = new FakeMainConfigurator();
        $injector = unserialize(
            serialize(
                new Injector($configurator)
            )
        );
        $comp = $injector->getInstance(FakeTraceClient::class);
        $this->assertEquals('(trace) aaa kurari bbb', $comp->intercepted());
    }
}
