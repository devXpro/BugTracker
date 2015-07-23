<?php

namespace BugBundle\DependencyInjection\Compiler;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class VoteCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('security.access.decision_manager')) {
            return;
        }
        $definition = $container->findDefinition(
            'security.access.decision_manager'
        );
        $definition->replaceArgument(2, true);
    }
}
