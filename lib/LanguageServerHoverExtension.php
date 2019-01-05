<?php

namespace Phpactor\Extension\LanguageServerHover;

use Phpactor\Container\Container;
use Phpactor\Container\ContainerBuilder;
use Phpactor\Container\Extension;
use Phpactor\Extension\Completion\CompletionExtension;
use Phpactor\Extension\LanguageServerHover\Handler\HoverHandler;
use Phpactor\Extension\LanguageServer\LanguageServerExtension;
use Phpactor\Extension\WorseReflection\WorseReflectionExtension;
use Phpactor\MapResolver\Resolver;

class LanguageServerHoverExtension implements Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(ContainerBuilder $container)
    {
        $container->register('language_server_hover.handler.hover', function (Container $container) {
            return new HoverHandler(
                $container->get(LanguageServerExtension::SERVICE_SESSION_WORKSPACE),
                $container->get(WorseReflectionExtension::SERVICE_REFLECTOR),
                $container->get(CompletionExtension::SERVICE_FORMATTER)
            );
        }, [ LanguageServerExtension::TAG_SESSION_HANDLER => []]);
    }

    /**
     * {@inheritDoc}
     */
    public function configure(Resolver $schema)
    {
    }
}
