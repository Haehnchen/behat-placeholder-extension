<?php
declare(strict_types = 1);

namespace espend\Behat\PlaceholderExtension\Utils;

/**
 * @author Daniel Espendiller <daniel@espendiller.net>
 */
final class PlaceholderUtil
{
    /**
     * @param string $placeholder
     * @return bool
     */
    public static function isValidPlaceholder(string $placeholder): bool
    {
        return
            isset($placeholder[0]) &&
            $placeholder[0] == '%' &&
            $placeholder[strlen($placeholder) - 1] === '%'
        ;
    }

    /**
     * @param string $placeholder
     */
    public static function isValidPlaceholderOrThrowException(string $placeholder)
    {
        if (!static::isValidPlaceholder($placeholder)) {
            throw new \RuntimeException(
                'Invalid placeholder given; please wrap your place with a percent sign %foobar%'
            );
        }
    }
}
