<?php

namespace Phpactor\Extension\LanguageServerHover\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Phpactor\Container\PhpactorContainer;
use Phpactor\Extension\ClassToFile\ClassToFileExtension;
use Phpactor\Extension\CompletionWorse\CompletionWorseExtension;
use Phpactor\Extension\Completion\CompletionExtension;
use Phpactor\Extension\ComposerAutoloader\ComposerAutoloaderExtension;
use Phpactor\Extension\LanguageServerHover\LanguageServerHoverExtension;
use Phpactor\Extension\LanguageServer\LanguageServerExtension;
use Phpactor\Extension\Logger\LoggingExtension;
use Phpactor\Extension\WorseReflection\WorseReflectionExtension;
use Phpactor\FilePathResolverExtension\FilePathResolverExtension;
use Phpactor\LanguageServer\LanguageServerBuilder;
use Phpactor\LanguageServer\Test\ServerTester;

class HoverTestCase extends TestCase
{
    public function createTester(): ServerTester
    {
        $container = PhpactorContainer::fromExtensions([
            WorseReflectionExtension::class,
            LanguageServerHoverExtension::class,
            LanguageServerExtension::class,
            LoggingExtension::class,
            ClassToFileExtension::class,
            CompletionExtension::class,
            CompletionWorseExtension::class,
            FilePathResolverExtension::class,
            ComposerAutoloaderExtension::class,
        ], [
            FilePathResolverExtension::PARAM_APPLICATION_ROOT => __DIR__ .'/../..'
        ]);

        $builder = $container->get(LanguageServerExtension::SERVICE_LANGUAGE_SERVER_BUILDER);
        assert($builder instanceof LanguageServerBuilder);
        return $builder->buildServerTester();
    }
}
