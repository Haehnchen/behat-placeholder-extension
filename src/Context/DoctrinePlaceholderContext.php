<?php
declare(strict_types = 1);

namespace espend\Behat\PlaceholderExtension\Context;

use Behat\Behat\Context\Context;
use Behat\Step\Given;
use Doctrine\Persistence\ManagerRegistry;
use espend\Behat\PlaceholderExtension\PlaceholderBagInterface;
use espend\Behat\PlaceholderExtension\Utils\PlaceholderUtil;
use Symfony\Component\PropertyAccess\Exception\AccessException;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * @author Daniel Espendiller <daniel@espendiller.net>
 */
class DoctrinePlaceholderContext implements Context, PlaceholderBagAwareContextInterface
{
    /**
     * @var PlaceholderBagInterface
     */
    private $placeholderBag;

    public function __construct(private readonly ManagerRegistry $doctrine)
    {
    }

    #[Given('/^set placeholder "([^"]*)" of "([^"]*)" on Doctrine model "([^"]*)" with "([^"]*)" equals "([^"]*)"$/')]
    public function setPlaceholderOfPropertyOnDoctrineModelWithCriteriaAndProperty(
        string $placeholder,
        string $property,
        string $model,
        string $leftOperator,
        string $rightOperator
    ): void {
        PlaceholderUtil::isValidPlaceholderOrThrowException($placeholder);

        $manager = $this->doctrine->getManagerForClass($model);
        if ($manager === null) {
            throw new \RuntimeException('No valid Doctrine manager found for ' . $model);
        }

        $object = $manager->getRepository($model)->findOneBy([$leftOperator => $rightOperator]);
        if ($object === null) {
            throw new \RuntimeException(
                sprintf('No valid model found "%s" "%s", "%s"', $model, $leftOperator . '=' . $rightOperator, $property)
            );
        }

        try {
            $value = PropertyAccess::createPropertyAccessor()->getValue($object, $property);
        } catch (AccessException $e) {
            throw new \RuntimeException('Invalid value not found: ' . $e->getMessage(), 0, $e);
        }

        $this->placeholderBag->add($placeholder, (string)$value);
    }

    /**
     * {@inheritdoc}
     */
    public function setPlaceholderBag(PlaceholderBagInterface $placeholderBag): void
    {
        $this->placeholderBag = $placeholderBag;
    }
}
