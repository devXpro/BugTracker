<?php

namespace BugBundle;

use BugBundle\DependencyInjection\Compiler\VoteCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class BugBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new VoteCompilerPass());
    }
}
