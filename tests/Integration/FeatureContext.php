<?php
declare(strict_types = 1);

namespace espend\Behat\PlaceholderExtension\Tests\Integration;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Step\Then;

final class FeatureContext implements Context
{
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
}
