<?php
declare(strict_types = 1);

namespace espend\Behat\PlaceholderExtension\Transformer;

use Behat\Behat\Definition\Call\DefinitionCall;
use Behat\Behat\Transformation\Transformer\ArgumentTransformer;
use espend\Behat\PlaceholderExtension\Context\PlaceholderContext;
use espend\Behat\PlaceholderExtension\Utils\PlaceholderUtil;

/**
 * @author Daniel Espendiller <daniel@espendiller.net>
 */
class PlaceholderArgumentTransformer implements ArgumentTransformer
{
    /**
     * {@inheritdoc}
     */
    public function supportsDefinitionAndArgument(DefinitionCall $definitionCall, $argumentIndex, $argumentValue)
    {
        // '%FOO%', '%foo%'
        return
            is_string($argumentValue) &&
            PlaceholderUtil::isValidPlaceholder($argumentValue) &&
            ($placeholders = $this->getPlaceholders($definitionCall)) &&
            isset($placeholders[$argumentValue])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function transformArgument(DefinitionCall $definitionCall, $argumentIndex, $argumentValue)
    {
        $placeholder = $this->getPlaceholders($definitionCall);
        if (isset($placeholder[$argumentValue])) {
            return $placeholder[$argumentValue];
        }

        return $argumentValue;
    }

    /**
     * Get current placeholder in context scenario
     *
     * @param DefinitionCall $definitionCall
     * @return array
     */
    private function getPlaceholders(DefinitionCall $definitionCall): array
    {
        /** @var \Behat\Behat\Context\Environment\InitializedContextEnvironment $environment */
        $environment = $definitionCall->getEnvironment();

        /** @var PlaceholderContext $context */
        $context = $environment->getContext(PlaceholderContext::class);

        return $context->getPlaceholders();
    }
}
