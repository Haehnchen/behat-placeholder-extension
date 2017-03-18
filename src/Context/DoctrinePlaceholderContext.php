<?php
declare(strict_types = 1);

namespace espend\Behat\PlaceholderExtension\Context;

use Behat\Behat\Context\Context;
use Behat\Symfony2Extension\Context\KernelDictionary;
use espend\Behat\PlaceholderExtension\PlaceholderBagInterface;
use espend\Behat\PlaceholderExtension\Utils\PlaceholderUtil;
use PHPUnit\Framework\Assert as Assertions;
use Symfony\Component\PropertyAccess\Exception\AccessException;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * @author Daniel Espendiller <daniel@espendiller.net>
 */
class DoctrinePlaceholderContext implements Context, PlaceholderBagAwareContextInterface
{
    use KernelDictionary;

    /**
     * @var PlaceholderBagInterface
     */
    private $placeholderBag;

    /**
     * @param string $placeholder
     * @param string $model Entity, Repository or class name
     * @param string $leftOperator filter property
     * @param string $rightOperator filter property value
     * @param string $property Symfony PropertyAccessor syntax eg "foo.bar" "foo[bar]"
     * @Given /^set placeholder "([^"]*)" of "([^"]*)" on Doctrine model "([^"]*)" with "([^"]*)" equals "([^"]*)"$/
     */
    public function setPlaceholderOfPropertyOnDoctrineModelWithCriteriaAndProperty(
        string $placeholder,
        string $property,
        string $model,
        string $leftOperator,
        string $rightOperator
    ) {
        PlaceholderUtil::isValidPlaceholderOrThrowException($placeholder);

        $manager = $this->getContainer()->get('doctrine')->getManagerForClass($model);
        Assertions::assertNotNull($manager, 'No valid Doctrine manager found for ' . $model);

        $object = $manager->getRepository($model)->findOneBy([$leftOperator => $rightOperator]);

        Assertions::assertNotNull(
            $object,
            sprintf('No valid model found "%s" "%s", "%s"', $model, $leftOperator . '=' . $rightOperator , $property)
        );

        try {
            $value = PropertyAccess::createPropertyAccessor()->getValue($object, $property);
        } catch (AccessException $e) {
            Assertions::fail('Invalid value not found: ' . $e->getMessage());
            return;
        }

        $this->placeholderBag->add($placeholder, (string)$value);
    }

    /**
     * {@inheritdoc}
     */
    public function setPlaceholderBag(PlaceholderBagInterface $placeholderBag)
    {
        $this->placeholderBag = $placeholderBag;
    }
}
