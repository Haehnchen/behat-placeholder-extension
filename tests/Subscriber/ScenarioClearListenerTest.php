<?php
declare(strict_types = 1);

namespace espend\Behat\PlaceholderExtension\Tests\Transformer;

use Behat\Behat\EventDispatcher\Event\ScenarioTested;
use espend\Behat\PlaceholderExtension\PlaceholderBag;
use espend\Behat\PlaceholderExtension\Subscriber\ScenarioClearListener;
use PHPUnit\Framework\TestCase;

/**
 * @author Daniel Espendiller <daniel@espendiller.net>
 */
class ScenarioClearListenerTest extends TestCase
{
    public function testThatClearIsEventMustResetBag()
    {
        $bag = new PlaceholderBag();
        $bag->add('%foobar%', 'foo');

        $scenario = new ScenarioClearListener($bag);
        $scenario->onBeforeAfterScenario();

        static::assertEmpty($bag->all());
    }

    public function testGetSubscribedEvents()
    {
        static::assertArrayHasKey(ScenarioTested::BEFORE, ScenarioClearListener::getSubscribedEvents());
        static::assertArrayHasKey(ScenarioTested::AFTER, ScenarioClearListener::getSubscribedEvents());
    }
}