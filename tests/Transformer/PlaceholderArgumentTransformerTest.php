<?php
declare(strict_types = 1);

namespace espend\Behat\PlaceholderExtension\Tests\Transformer;

use Behat\Behat\Definition\Call\DefinitionCall;
use Behat\Behat\Definition\Definition;
use Behat\Gherkin\Node\FeatureNode;
use Behat\Gherkin\Node\StepNode;
use Behat\Testwork\Call\Callee;
use Behat\Testwork\Environment\Environment;
use espend\Behat\PlaceholderExtension\Context\PlaceholderContext;
use espend\Behat\PlaceholderExtension\Transformer\PlaceholderArgumentTransformer;
use PHPUnit\Framework\TestCase;

/**
 * @author Daniel Espendiller <daniel@espendiller.net>
 */
class PlaceholderArgumentTransformerTest extends TestCase
{
    /**
     * @dataProvider dataTransformArgument
     * @param string $actual
     * @param bool $expected
     */
    public function testTransformArgument(string $actual, bool $expected)
    {
        $call = $this->createDefinitionCall();

        $transformer = new PlaceholderArgumentTransformer();

        static::assertEquals(
            'foo',
            $transformer->transformArgument($call, 0, '%foobar%')
        );

        static::assertEquals(
            'foobar%',
            $transformer->transformArgument($call, 0, 'foobar%')
        );
    }

    /**
     * @dataProvider dataSupports
     * @param string $actual
     * @param bool $expected
     */
    public function testSupportsDefinitionAndArgument(string $actual, bool $expected)
    {
        $call = $this->createDefinitionCall();

        $transformer = new PlaceholderArgumentTransformer();

        static::assertEquals(
            $expected,
            $transformer->supportsDefinitionAndArgument($call, 0, $actual)
        );
    }

    /**
     * @return array
     */
    public function dataSupports()
    {
        return [
            ['%foobar%', true],
            ['a%foobar%', false],
            ['%foobar', false],
            ['foobar%', false]
        ];
    }

    /**
     * @return array
     */
    public function dataTransformArgument()
    {
        return [
            ['%foobar%', true],
            ['a%foobar%', false],
            ['%foobar', false],
            ['foobar%', false]
        ];
    }

    /**
     * Workarounds for "final" in implementation of Behat
     *
     * @return DefinitionCall
     */
    private function createDefinitionCall(): DefinitionCall
    {
        $context = $this->createMock(PlaceholderContext::class);
        $context->method('getPlaceholders')
            ->willReturn(['%foobar%' => 'foo']);

        $env = new class($context) implements Environment
        {
            private $context;

            public function __construct($context)
            {
                $this->context = $context;
            }

            public function getSuite()
            {
            }

            public function bindCallee(Callee $callee)
            {
            }

            public function getContext()
            {
                return $this->context;
            }
        };

        return new DefinitionCall(
            $env,
            $this->createMock(FeatureNode::class),
            $this->createMock(StepNode::class),
            $this->createMock(Definition::class),
            []
        );
    }
}
