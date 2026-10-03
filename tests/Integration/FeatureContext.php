<?php
declare(strict_types = 1);

namespace espend\Behat\PlaceholderExtension\Tests\Integration;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Step\DocString;
use Behat\Step\Then;
use espend\Behat\PlaceholderExtension\Context\PlaceholderBagAwareContextInterface;
use espend\Behat\PlaceholderExtension\PlaceholderBagInterface;

final class FeatureContext implements Context, PlaceholderBagAwareContextInterface
{
    private PlaceholderBagInterface $placeholderBag;

    public function setPlaceholderBag(PlaceholderBagInterface $placeholderBag): void
    {
        $this->placeholderBag = $placeholderBag;
    }

    #[Then('the placeholder bag should be empty')]
    public function assertPlaceholderBagIsEmpty(): void
    {
        if ($this->placeholderBag->all() !== []) {
            throw new \RuntimeException('Placeholders leaked from a previous scenario.');
        }
    }

    #[Then('/^the transformed value "([^"]*)" should equal "([^"]*)"$/')]
    public function assertTransformedValue(string $actual, string $expected): void
    {
        if ($actual !== $expected) {
            throw new \RuntimeException(sprintf('Expected "%s", got "%s".', $expected, $actual));
        }
    }

    #[Then('/^the transformed multiline value should equal "([^"]*)":$/')]
    public function assertTransformedMultilineValue(string $expected, PyStringNode $actual): void
    {
        if ($actual->getRaw() !== $expected) {
            throw new \RuntimeException(sprintf('Expected "%s", got "%s".', $expected, $actual->getRaw()));
        }
    }

    #[Then('/^the transformed doc string should equal "([^"]*)":$/')]
    public function assertTransformedDocString(string $expected, DocString $actual): void
    {
        if ($actual->getContent() !== $expected) {
            throw new \RuntimeException(sprintf('Expected "%s", got "%s".', $expected, $actual->getContent()));
        }
    }
}
