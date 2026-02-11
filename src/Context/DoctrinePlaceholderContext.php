<?php
declare(strict_types = 1);

namespace espend\Behat\PlaceholderExtension\Context;

use Behat\Behat\Context\Context;
use Behat\Step\Given;
use Symfony\Component\DependencyInjection\ContainerInterface;
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
    /**
     * @var ContainerInterface|null
     */
    private $container;

    /**
     * @var PlaceholderBagInterface
     */
    private $placeholderBag;

    #[Given('/^set placeholder "([^"]*)" of "([^"]*)" on Doctrine model "([^"]*)" with "([^"]*)" equals "([^"]*)"$/')]
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

    /**
     * Sets the container.
     *
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Gets the container.
     *
     * @return ContainerInterface
     */
    protected function getContainer(): ContainerInterface
    {
        if ($this->container === null) {
            throw new \RuntimeException('Container not set. Make sure to call setContainer() first.');
        }
        return $this->container;
    }
}
