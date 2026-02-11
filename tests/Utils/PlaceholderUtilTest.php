<?php
declare(strict_types = 1);

namespace espend\Behat\PlaceholderExtension\Tests\Utils;

use espend\Behat\PlaceholderExtension\Utils\PlaceholderUtil;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @author Daniel Espendiller <daniel@espendiller.net>
 */
class PlaceholderUtilTest extends TestCase
{
    /**
     * @return array
     */
    public static function dataPlaceholder(): array
    {
        return [
            ['%foobar%', true],
            ['%foo', false],
            ['foobar%', false],
            ['a%foobar%', false],
        ];
    }

    #[DataProvider('dataPlaceholder')]
    public function testIsValidPlaceholder(string $placeholder, bool $expected): void
    {
        static::assertEquals($expected, PlaceholderUtil::isValidPlaceholder($placeholder));
    }

    public function testIsValidPlaceholderOrThrowException(): void
    {
        $this->expectException(\RuntimeException::class);
        PlaceholderUtil::isValidPlaceholderOrThrowException('foo%');
    }
}
