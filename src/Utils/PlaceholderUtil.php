<?php

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
}
