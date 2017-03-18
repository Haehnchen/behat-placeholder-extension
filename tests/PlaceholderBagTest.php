<?php
declare(strict_types = 1);

namespace espend\Behat\PlaceholderExtension\Tests;

use espend\Behat\PlaceholderExtension\PlaceholderBag;
use PHPUnit\Framework\TestCase;

/**
 * @author Daniel Espendiller <daniel@espendiller.net>
 */
class PlaceholderBagTest extends TestCase
{
    /**
     * @expectedException \RuntimeException
     */
    public function testInvalidPlaceholderMustThrowException()
    {
        $bag = new PlaceholderBag();
        $bag->add('foo%', 'foo');
    }

    public function testAddPlaceholder()
    {
        $bag = new PlaceholderBag();
        $bag->add('%foo%', 'foo');

        static::assertEquals(['%foo%' => 'foo'], $bag->all());
    }

    public function testClearCondition()
    {
        $bag = new PlaceholderBag();
        $bag->add('%foo%', 'foo');
        $bag->clear();

        static::assertEmpty($bag->all());
    }
}