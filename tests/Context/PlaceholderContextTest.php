<?php
declare(strict_types = 1);

namespace espend\Behat\PlaceholderExtension\Tests\Context;

use DateTime;
use espend\Behat\PlaceholderExtension\Context\PlaceholderContext;
use PHPUnit\Framework\TestCase;

/**
 * @author Daniel Espendiller <daniel@espendiller.net>
 */
class PlaceholderContextTest extends TestCase
{
    public function testICreateARandomMailPlaceholder()
    {
        $context = new PlaceholderContext();
        $context->iCreateARandomMailPlaceholder('%mail%');

        static::assertArrayHasKey('%mail%', $context->getPlaceholders());
        static::assertRegExp('/^behat-.*@example.com$/', $context->getPlaceholders()['%mail%']);
    }

    public function testSetRandomizedMailWithValidValue()
    {
        $context = new PlaceholderContext();
        $context->setRandomizedMail('behat-%random%@google.com');
        $context->iCreateARandomMailPlaceholder('%mail%');

        static::assertRegExp('/^behat-.*@google.com$/', $context->getPlaceholders()['%mail%']);
    }

    public function testISetAPlaceholderWithValue()
    {
        $context = new PlaceholderContext();
        $context->iSetAPlaceholderWithValue('%mail%', 'foobar');

        static::assertEquals('foobar', $context->getPlaceholders()['%mail%']);
    }

    public function testICreateARandomPasswordPlaceholder()
    {
        $context = new PlaceholderContext();
        $context->iCreateARandomPasswordPlaceholder('%password%');

        static::assertArrayHasKey('%password%', $context->getPlaceholders());
    }

    public function testThatScenarioMustClearPlaceholders()
    {
        $context = new PlaceholderContext();
        $context->iSetAPlaceholderWithValue('%foobar%', 'foobar');
        $context->beforeAndAfterScenario();

        static::assertEmpty($context->getPlaceholders());
    }

    public function testISetCurrentDatetimeAsFormatInPlaceholder()
    {
        $context = new PlaceholderContext();
        $context->iSetCurrentDatetimeAsFormatInPlaceholder('y-m-d', '%foobar%');

        static::assertInstanceOf(
            DateTime::class,
            DateTime::createFromFormat('y-m-d', $context->getPlaceholders()['%foobar%'])
        );
    }

    public function testISetARandomTextWithLengthInPlaceholder()
    {
        $context = new PlaceholderContext();
        $context->iSetARandomTextWithLengthInPlaceholder('50', '%foobar%');

        static::assertEquals(
            50,
            strlen($context->getPlaceholders()['%foobar%'])
        );
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testAddPlaceholderPlaceholder()
    {
        $context = new PlaceholderContext();
        $context->addPlaceholder('%mail', 'foobar');
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testISetAPlaceholderWithValueButInvalidPlaceholderPattern()
    {
        $context = new PlaceholderContext();
        $context->iSetAPlaceholderWithValue('%mail', 'foobar');
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testSetRandomizedMailWithInvalidValue()
    {
        (new PlaceholderContext())->setRandomizedMail('invalid');
    }
}