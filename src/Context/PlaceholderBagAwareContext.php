<?php
declare(strict_types = 1);

namespace espend\Behat\PlaceholderExtension\Context;

use espend\Behat\PlaceholderExtension\PlaceholderBagInterface;

/**
 * @author Daniel Espendiller <daniel@espendiller.net>
 */
interface PlaceholderBagAwareContext
{
    /**
     * Sets place holder bag for context
     *
     * @param PlaceholderBagInterface $placeholderBag
     * @return void
     */
    public function setPlaceholderBag(PlaceholderBagInterface $placeholderBag);
}