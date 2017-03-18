<?php
declare(strict_types = 1);

namespace espend\Behat\PlaceholderExtension\Tests;

use espend\Behat\PlaceholderExtension\PlaceholderExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Daniel Espendiller <daniel@espendiller.net>
 */
class PlaceholderExtensionTest extends TestCase
{
    public function testContainerLoad()
    {
        $container = new ContainerBuilder();

        $extension = new PlaceholderExtension();
        $extension->load($container, []);

        static::assertTrue(
            $container->has('espend.behat.placeholder_extension.placeholder_bag')
        );

        static::assertTrue(
            $container->has('espend.behat.placeholder_extension.subscriber.scenario_clear_listener')
        );

        static::assertTrue(
            $container->has('espend.behat.placeholder_extension.transformer.placeholder_argument_transformer')
        );

        static::assertTrue(
            $container->has('espend.behat.placeholder_extension.context.initializer.placeholder_bag_aware_initializer')
        );
    }
}
