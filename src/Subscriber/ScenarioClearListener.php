<?php
declare(strict_types = 1);

namespace espend\Behat\PlaceholderExtension\Subscriber;

use Behat\Behat\EventDispatcher\Event\ScenarioTested;
use espend\Behat\PlaceholderExtension\PlaceholderBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Daniel Espendiller <daniel@espendiller.net>
 */
class ScenarioClearListener implements EventSubscriberInterface
{
    /**
     * @var PlaceholderBagInterface
     */
    private $parameterBag;

    /**
     * @param PlaceholderBagInterface $parameterBag
     */
    public function __construct(PlaceholderBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    /**
     * Placeholder are only valid per scenario scope.
     */
    public function onBeforeAfterScenario()
    {
        $this->parameterBag->clear();
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            ScenarioTested::BEFORE => 'onBeforeAfterScenario',
            ScenarioTested::AFTER => 'onBeforeAfterScenario'
        );
    }
}
