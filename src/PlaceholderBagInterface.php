<?php
declare(strict_types = 1);

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
    public function add(string $placeholder, string $value);

    /**
     * Clears all attributes
     *
     * @return void
     */
    public function clear();

    /**
     * Get all attributes
     *
     * @return array
     */
    public function all() : array;
}
