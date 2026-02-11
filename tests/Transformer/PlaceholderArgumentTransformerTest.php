<?php
declare(strict_types = 1);

namespace espend\Behat\PlaceholderExtension\Tests\Transformer;

use Behat\Behat\Definition\Call\DefinitionCall;
use Behat\Behat\Definition\Definition;
use Behat\Gherkin\Node\FeatureNode;
use Behat\Gherkin\Node\StepNode;
use Behat\Testwork\Environment\Environment;
use espend\Behat\PlaceholderExtension\PlaceholderBag;
use espend\Behat\PlaceholderExtension\Transformer\PlaceholderArgumentTransformer;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @author Daniel Espendiller <daniel@espendiller.net>
 */
class PlaceholderArgumentTransformerTest extends TestCase
{
    #[DataProvider('dataTransformArgument')]
    public function testTransformArgument(string $actual, string $expected): void
    {
        $call = $this->createDefinitionCall();

        $bag = new PlaceholderBag();
        $bag->add('%foobar%', 'foo');

        $transformer = new PlaceholderArgumentTransformer($bag);

        static::assertEquals(
            $expected,
            $transformer->transformArgument($call, 0, $actual)
        );
    }

    #[DataProvider('dataSupports')]
    public function testSupportsDefinitionAndArgument(string $actual, bool $expected): void
    {
        $call = $this->createDefinitionCall();

        $bag = new PlaceholderBag();
        $bag->add('%foobar%', 'foo');

        $transformer = new PlaceholderArgumentTransformer($bag);

        static::assertEquals(
            $expected,
            $transformer->supportsDefinitionAndArgument($call, 0, $actual)
        );
    }

    /**
     * @return array
     */
    public static function dataSupports(): array
    {
        return [
            ['%foobar%', true],
            ['a%foobar%', true],
            ['%foobar', false],
            ['foobar%', false]
        ];
    }

    /**
     * @return array
     */
    public static function dataTransformArgument(): array
    {
        return [
            ['%foobar%', 'foo'],
            ['a%foobar%', 'afoo'],
            ['%foobar', '%foobar'],
            ['foobar%', 'foobar%']
        ];
    }

    /**
     * Workarounds for "final" in implementation of Behat
     *
     * @return DefinitionCall
     */
    private function createDefinitionCall(): DefinitionCall
    {
        return new DefinitionCall(
            $this->createMock(Environment::class),
            $this->createMock(FeatureNode::class),
            $this->createMock(StepNode::class),
            $this->createMock(Definition::class),
            []
        );
    }
}
