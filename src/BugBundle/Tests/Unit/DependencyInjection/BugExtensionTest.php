<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 21.07.15
 * Time: 21:17
 */

namespace BugBundle\Tests\Unit\DependencyInjection;


use BugBundle\DependencyInjection\BugExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;


class BugExtensionTest extends \PHPUnit_Framework_TestCase
{


    public function testBug()
    {
        $container = new ContainerBuilder();
        $extension = new BugExtension();
        $extension->load(array(), $container);
    }
}
