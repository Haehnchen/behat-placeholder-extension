<?php
declare(strict_types = 1);

namespace espend\Behat\PlaceholderExtension\Transformer;

use Behat\Behat\Definition\Call\DefinitionCall;
use Behat\Behat\Transformation\Transformer\ArgumentTransformer;
use espend\Behat\PlaceholderExtension\PlaceholderBagInterface;
use espend\Behat\PlaceholderExtension\Utils\PlaceholderUtil;

/**
 * @author Daniel Espendiller <daniel@espendiller.net>
 */
class PlaceholderArgumentTransformer implements ArgumentTransformer
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
    public function supportsDefinitionAndArgument(DefinitionCall $definitionCall, $argumentIndex, $argumentValue)
    {
        // '%FOO%', '%foo%'
        return
            is_string($argumentValue) &&
            PlaceholderUtil::isValidPlaceholder($argumentValue) &&
            ($placeholders = $this->placeholderBag->all()) &&
            isset($placeholders[$argumentValue])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function transformArgument(DefinitionCall $definitionCall, $argumentIndex, $argumentValue)
    {
        $placeholder = $this->placeholderBag->all();
        if (isset($placeholder[$argumentValue])) {
            return $placeholder[$argumentValue];
        }

        return $argumentValue;
    }
}
