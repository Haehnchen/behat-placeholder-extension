<?php
declare(strict_types = 1);

namespace espend\Behat\PlaceholderExtension;

use Behat\Behat\Transformation\ServiceContainer\TransformationExtension;
use Behat\Testwork\ServiceContainer\Extension;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use espend\Behat\PlaceholderExtension\Transformer\PlaceholderArgumentTransformer;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Daniel Espendiller <daniel@espendiller.net>
 */
class PlaceholderExtension implements Extension
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getConfigKey()
    {
        return 'placeholder';
    }

    /**
     * {@inheritdoc}
     */
    public function initialize(ExtensionManager $extensionManager)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function configure(ArrayNodeDefinition $builder)
    {
        $builder
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('mail_randomize_template')
                    ->defaultValue('behat-%random%@example.com')
                    ->end()
                ->end()
            ->end();
    }

    /**
     * {@inheritdoc}
     */
    public function load(ContainerBuilder $container, array $config)
    {
        $this->loadPlaceholdersTransformer($container);
    }

    /**
     * Loads transformers
     *
     * @param ContainerBuilder $container
     */
    private function loadPlaceholdersTransformer(ContainerBuilder $container)
    {
        $container
            ->register('espend.behat.placeholder_extension', PlaceholderArgumentTransformer::class)
            ->addTag(TransformationExtension::ARGUMENT_TRANSFORMER_TAG, ['priority' => 1000]);
    }
}
