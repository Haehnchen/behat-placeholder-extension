<?php
declare(strict_types = 1);

namespace espend\Behat\PlaceholderExtension\Context\Initializer;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\Initializer\ContextInitializer;
use espend\Behat\PlaceholderExtension\Context\PlaceholderBagAwareContextInterface;
use espend\Behat\PlaceholderExtension\PlaceholderBagInterface;

/**
 * @author Daniel Espendiller <daniel@espendiller.net>
 */
class PlaceholderBagAwareInitializer implements ContextInitializer
{
    /**
     * @var PlaceholderBagInterface
     */
    private $placeholderBag;

    /**
     * @param PlaceholderBagInterface $placeholderBag
     */
    public function __construct(PlaceholderBagInterface $placeholderBag)
    {
        $this->placeholderBag = $placeholderBag;
    }

    /**
     * {@inheritdoc}
     */
    public function initializeContext(Context $context)
    {
        if ($context instanceof PlaceholderBagAwareContextInterface) {
            $context->setPlaceholderBag($this->placeholderBag);
        }
    }
}
