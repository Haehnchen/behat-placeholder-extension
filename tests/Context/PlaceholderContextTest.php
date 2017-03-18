<?php
declare(strict_types = 1);

namespace espend\Behat\PlaceholderExtension\Tests\Context;

use DateTime;
use espend\Behat\PlaceholderExtension\Context\PlaceholderContext;
use espend\Behat\PlaceholderExtension\PlaceholderBag;
use PHPUnit\Framework\TestCase;

/**
 * @author Daniel Espendiller <daniel@espendiller.net>
 */
class PlaceholderContextTest extends TestCase
{
    public function testICreateARandomMailPlaceholder()
    {
        $bag = new PlaceholderBag();

        $context = new PlaceholderContext();
        $context->setPlaceholderBag($bag);

        $context->iCreateARandomMailPlaceholder('%mail%');

        static::assertArrayHasKey('%mail%', $bag->all());
        static::assertRegExp('/^behat-.*@example.com$/', $bag->all()['%mail%']);
    }

    public function testSetRandomizedMailWithValidValue()
    {
        $bag = new PlaceholderBag();

        $context = new PlaceholderContext();
        $context->setPlaceholderBag($bag);
        $context->setRandomizedMail('behat-%random%@google.com');
        $context->iCreateARandomMailPlaceholder('%mail%');

        static::assertRegExp('/^behat-.*@google.com$/', $bag->all()['%mail%']);
    }

    public function testISetAPlaceholderWithValue()
    {
        $bag = new PlaceholderBag();

        $context = new PlaceholderContext();
        $context->setPlaceholderBag($bag);
        $context->iSetAPlaceholderWithValue('%mail%', 'foobar');

        static::assertEquals('foobar', $bag->all()['%mail%']);
    }

    public function testICreateARandomPasswordPlaceholder()
    {
        $bag = new PlaceholderBag();

        $context = new PlaceholderContext();
        $context->setPlaceholderBag($bag);
        $context->iCreateARandomPasswordPlaceholder('%password%');

        static::assertArrayHasKey('%password%', $bag->all());
    }

    public function testISetCurrentDatetimeAsFormatInPlaceholder()
    {
        $bag = new PlaceholderBag();

        $context = new PlaceholderContext();
        $context->setPlaceholderBag($bag);
        $context->iSetCurrentDatetimeAsFormatInPlaceholder('y-m-d', '%foobar%');

        static::assertInstanceOf(
            DateTime::class,
            DateTime::createFromFormat('y-m-d', $bag->all()['%foobar%'])
        );
    }

    public function testISetARandomTextWithLengthInPlaceholder()
    {
        $bag = new PlaceholderBag();

        $context = new PlaceholderContext();
        $context->setPlaceholderBag($bag);

        $context->iSetARandomTextWithLengthInPlaceholder('50', '%foobar%');

        static::assertEquals(
            50,
            strlen($bag->all()['%foobar%'])
        );
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testISetAPlaceholderWithValueButInvalidPlaceholderPattern()
    {
        $context = new PlaceholderContext();
        $context->setPlaceholderBag(new PlaceholderBag());

        $context->iSetAPlaceholderWithValue('%mail', 'foobar');
    }

    public function testPrintPlaceholderValueOf()
    {
        $bag = new PlaceholderBag();
        $bag->add('%mail%', 'foo');

        $context = new PlaceholderContext();
        $context->setPlaceholderBag($bag);

        ob_start();
        $context->printPlaceholderValueOf('%mail%');
        $output = ob_get_clean();

        static::assertContains('Placeholder "%mail%": "foo"', $output);
    }

    public function testPrintAllPlaceholder()
    {
        $bag = new PlaceholderBag();
        $bag->add('%mail%', 'foo');

        $context = new PlaceholderContext();
        $context->setPlaceholderBag($bag);

        ob_start();
        $context->printAllPlaceholder();
        $output = ob_get_clean();

        static::assertContains('Placeholder "%mail%": "foo"', $output);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testSetRandomizedMailWithInvalidValue()
    {
        (new PlaceholderContext())->setRandomizedMail('invalid');
    }
}