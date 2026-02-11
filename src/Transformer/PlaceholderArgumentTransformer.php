<?php
declare(strict_types = 1);

namespace espend\Behat\PlaceholderExtension\Transformer;

use Behat\Behat\Transformation\Scope\TransformationScope;
use Behat\Behat\Transformation\Transformer\ArgumentTransformer;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Step\DocString;
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

    public function supportsDefinitionAndArgument(
        TransformationScope $scope,
        int|string $argumentIndex,
        mixed $argumentValue
    ): bool
    {
        if ($argumentValue instanceof PyStringNode) {
            $argumentValue = $argumentValue->getRaw();
        } elseif ($argumentValue instanceof DocString) {
            $argumentValue = $argumentValue->getContent();
        }

        if (!is_string($argumentValue)) {
            return false;
        }

        // '%FOO%', '%foo%'
        if (PlaceholderUtil::isValidPlaceholder($argumentValue)) {
            return ($placeholders = $this->placeholderBag->all())
                && isset($placeholders[$argumentValue]);
        }

        // 'foobar%FOO%'
        foreach ($this->placeholderBag->all() as $key => $value) {
            if (false !== strpos($argumentValue, $key)) {
                return true;
            }
        }

        return false;
    }

    public function transformArgument(
        TransformationScope $scope,
        int|string $argumentIndex,
        mixed $argumentValue
    ): mixed
    {
        $originalArgument = $argumentValue;

        if ($argumentValue instanceof PyStringNode) {
            $argumentValue = $argumentValue->getRaw();
        } elseif ($argumentValue instanceof DocString) {
            $argumentValue = $argumentValue->getContent();
        }

        // 'foobar%FOO%'
        foreach ($this->placeholderBag->all() as $key => $value) {
            $argumentValue = str_replace($key, $value, $argumentValue);
        }

        if ($originalArgument instanceof PyStringNode) {
            return new PyStringNode(explode("\n", $argumentValue), $originalArgument->getLine());
        }

        if ($originalArgument instanceof DocString) {
            return new DocString(new PyStringNode(explode("\n", $argumentValue), 0));
        }

        $placeholders = $this->placeholderBag->all();
        if (isset($placeholders[$argumentValue])) {
            return $placeholders[$argumentValue];
        }

        return $argumentValue;
    }
}
