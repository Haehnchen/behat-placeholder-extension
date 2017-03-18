<?php
declare(strict_types = 1);

namespace espend\Behat\PlaceholderExtension\Tests\Utils;

use espend\Behat\PlaceholderExtension\Utils\PlaceholderUtil;
use PHPUnit\Framework\TestCase;

/**
 * @author Daniel Espendiller <daniel@espendiller.net>
 */
class PlaceholderUtilTest extends TestCase
{
    public function dataPlaceholder()
    {
        return [
            ['%foobar%', true],
            ['%foo', false],
            ['foobar%', false],
            ['a%foobar%', false],
        ];
    }

    /**
     * @dataProvider dataPlaceholder
     * @param string $placeholder
     * @param bool $expected
     */
    public function testIsValidPlaceholder(string $placeholder, bool $expected)
    {
        static::assertEquals($expected, PlaceholderUtil::isValidPlaceholder($placeholder));
    }
}