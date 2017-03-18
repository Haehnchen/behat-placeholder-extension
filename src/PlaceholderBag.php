<?php
declare(strict_types = 1);

namespace espend\Behat\PlaceholderExtension;

use espend\Behat\PlaceholderExtension\Utils\PlaceholderUtil;

/**
 * @author Daniel Espendiller <daniel@espendiller.net>
 */
class PlaceholderBag implements PlaceholderBagInterface
{
    /**
     * @var array
     */
    private $placeholder = [];

    /**
     * {@inheritdoc}
     */
    public function add(string $placeholder, string $value)
    {
        PlaceholderUtil::isValidPlaceholderOrThrowException($placeholder);

        $this->placeholder[$placeholder] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $this->placeholder = [];
    }

    /**
     * {@inheritdoc}
     */
    public function all(): array
    {
        return $this->placeholder;
    }
}
