<?php

namespace espend\Behat\PlaceholderExtension;

/**
 * @author Daniel Espendiller <daniel@espendiller.net>
 */
interface PlaceholderBagInterface
{
    /**
     * Add a placeholder value, only "%foobar%" format must be allowed
     *
     * @param string $placeholder "%foobar%"
     * @param string $value
     * @return void
     */
    public function addPlaceholder(string $placeholder, string $value);
}
