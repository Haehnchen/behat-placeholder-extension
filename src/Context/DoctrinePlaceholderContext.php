<?php

namespace espend\Behat\PlaceholderExtension\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Symfony2Extension\Context\KernelDictionary;
use espend\Behat\PlaceholderExtension\PlaceholderBagInterface;
use PHPUnit\Framework\Assert as Assertions;
use Symfony\Component\PropertyAccess\Exception\AccessException;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * @author Daniel Espendiller <daniel@espendiller.net>
 */
class DoctrinePlaceholderContext implements Context
{
    use KernelDictionary;

    /**
     * @var PlaceholderBagInterface
     */
    private $placeholderBag;

    /**
     * Pipe main mink context
     *
     * @BeforeScenario
     * @param BeforeScenarioScope $scope
     */
    public function before(BeforeScenarioScope $scope)
    {
        /** @var \Behat\Behat\Context\Environment\InitializedContextEnvironment $environment */
        $environment = $scope->getEnvironment();

        $this->placeholderBag = $environment->getContext(PlaceholderContext::class);
    }

    /**
     * @param string $placeholder
     * @param string $model Entity, Repository or class name
     * @param string $criteria properties to filter on eg "id=foobar" parse_str supported
     * @param string $property Symfony PropertyAccessor syntax eg "foo.bar" "foo[bar]"
     * @Given /^I set a placeholder "([^"]*)" on Doctrine model "([^"]*)" with "([^"]*)" and "([^"]*)"$/
     */
    public function iSetAPlaceholderOnDoctrineModelWithCriteriaAndProperty(
        string $placeholder,
        string $model,
        string $criteria,
        string $property
    ) {
        $manager = $this->getContainer()->get('doctrine')->getManagerForClass($model);
        Assertions::assertNotNull($manager, 'No valid Doctrine manager found for ' . $model);

        parse_str($criteria, $find);

        $object = $manager->getRepository($model)->findOneBy($find);

        Assertions::assertNotNull(
            $object,
            sprintf('No valid model found "%s" "%s", "%s"', $model, $criteria, $property)
        );

        try {
            $value = PropertyAccess::createPropertyAccessor()->getValue($object, $property);
        } catch (AccessException $e) {
            Assertions::fail('Invalid value not found: ' . $e->getMessage());
            return;
        }

        $this->placeholderBag->addPlaceholder($placeholder, (string)$value);
    }
}
