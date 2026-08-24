<?php
declare(strict_types = 1);

namespace espend\Behat\PlaceholderExtension\Transformer;

use Behat\Behat\Definition\Call\DefinitionCall;
use Behat\Behat\Transformation\Transformer\ArgumentTransformer;
use Behat\Gherkin\Node\PyStringNode;
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
     * The native mixed type keeps this implementation contravariant with Behat 4
     * while remaining compatible with Behat 3's untyped parameter.
     *
     * @param int|string $argumentIndex
     */
    public function supportsDefinitionAndArgument(
        DefinitionCall $definitionCall,
        mixed $argumentIndex,
        mixed $argumentValue
    ): bool
    {
        if ($argumentValue instanceof PyStringNode) {
            $argumentValue = $argumentValue->getRaw();
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

    /**
     * The native mixed type keeps this implementation contravariant with Behat 4
     * while remaining compatible with Behat 3's untyped parameter.
     *
     * @param int|string $argumentIndex
     */
    public function transformArgument(
        DefinitionCall $definitionCall,
        mixed $argumentIndex,
        mixed $argumentValue
    ): mixed
    {
        $isPyStringNode = $argumentValue instanceof PyStringNode;

        if ($isPyStringNode) {
            $argumentValue = $argumentValue->getRaw();
        }

        // 'foobar%FOO%'
        foreach ($this->placeholderBag->all() as $key => $value) {
            $argumentValue = str_replace($key, $value, $argumentValue);
        }

        // '%FOO%', '%foo%'
        $placeholder = $this->placeholderBag->all();
        if (isset($placeholder[$argumentValue])) {
            return $placeholder[$argumentValue];
        }

        if ($isPyStringNode) {
            return new PyStringNode(explode("\n", $argumentValue), 0);
        }

        return $argumentValue;
    }
}
