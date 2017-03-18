<?php
declare(strict_types = 1);

namespace espend\Behat\PlaceholderExtension\Tests\Initializer;

use Behat\Behat\Context\Context;
use espend\Behat\PlaceholderExtension\Context\Initializer\PlaceholderBagAwareInitializer;
use espend\Behat\PlaceholderExtension\Context\PlaceholderBagAwareContextInterface;
use espend\Behat\PlaceholderExtension\PlaceholderBag;
use espend\Behat\PlaceholderExtension\PlaceholderBagInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author Daniel Espendiller <daniel@espendiller.net>
 */
class PlaceholderBagAwareInitializerTest extends TestCase
{
    public function testThatInitializerAddClass()
    {
        $bag = new PlaceholderBag();

        $initializer = new PlaceholderBagAwareInitializer($bag);
        $context = new class implements Context, PlaceholderBagAwareContextInterface
        {
            private $placeholder;

            /**
             * @return PlaceholderBagInterface
             */
            public function getPlaceholder()
            {
                return $this->placeholder;
            }
            /**
             * {@inheritdoc}
             */
            public function setPlaceholderBag(PlaceholderBagInterface $placeholderBag)
            {
                $this->placeholder = $placeholderBag;
            }
        };

        $initializer->initializeContext($context);

        static::assertInstanceOf(PlaceholderBagInterface::class, $context->getPlaceholder());
    }
}