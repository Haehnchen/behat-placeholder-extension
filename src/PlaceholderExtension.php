<?php
declare(strict_types = 1);

namespace espend\Behat\PlaceholderExtension;

use Behat\Behat\Context\ServiceContainer\ContextExtension;
use Behat\Behat\EventDispatcher\ServiceContainer\EventDispatcherExtension;
use Behat\Behat\Transformation\ServiceContainer\TransformationExtension;
use Behat\Testwork\ServiceContainer\Extension;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use espend\Behat\PlaceholderExtension\Context\Initializer\PlaceholderBagAwareInitializer;
use espend\Behat\PlaceholderExtension\Subscriber\ScenarioClearListener;
use espend\Behat\PlaceholderExtension\Transformer\PlaceholderArgumentTransformer;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

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
        $container->register('espend.behat.placeholder_extension.placeholder_bag', PlaceholderBag::class);

        $container
            ->register(
                'espend.behat.placeholder_extension.subscriber.scenario_clear_listener',
                ScenarioClearListener::class
            )
            ->addTag(EventDispatcherExtension::SUBSCRIBER_TAG)
            ->addArgument(new Reference('espend.behat.placeholder_extension.placeholder_bag'));

        $this->loadPlaceholdersTransformer($container);
        $this->loadContextInitializer($container);
    }

    /**
     * Loads transformers
     *
     * @param ContainerBuilder $container
     */
    private function loadPlaceholdersTransformer(ContainerBuilder $container)
    {
        $container
            ->register(
                'espend.behat.placeholder_extension.transformer.placeholder_argument_transformer',
                PlaceholderArgumentTransformer::class
            )
            ->addArgument(new Reference('espend.behat.placeholder_extension.placeholder_bag'))
            ->addTag(TransformationExtension::ARGUMENT_TRANSFORMER_TAG, ['priority' => 1000]);
    }

    /**
     * @param ContainerBuilder $container
     */
    private function loadContextInitializer(ContainerBuilder $container)
    {
        $container
            ->register(
                'espend.behat.placeholder_extension.context.initializer.placeholder_bag_aware_initializer',
                PlaceholderBagAwareInitializer::class
            )
            ->addArgument(new Reference('espend.behat.placeholder_extension.placeholder_bag'))
            ->addTag(ContextExtension::INITIALIZER_TAG);
    }
}
