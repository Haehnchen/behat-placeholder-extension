<?php
declare(strict_types = 1);

namespace espend\Behat\PlaceholderExtension\Tests\Transformer;

use Behat\Behat\Transformation\Scope\TransformationScope;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Step\DocString;
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
        $scope = $this->createTransformationScope();

        $bag = new PlaceholderBag();
        $bag->add('%foobar%', 'foo');

        $transformer = new PlaceholderArgumentTransformer($bag);

        static::assertEquals(
            $expected,
            $transformer->transformArgument($scope, 0, $actual)
        );
    }

    #[DataProvider('dataSupports')]
    public function testSupportsDefinitionAndArgument(string $actual, bool $expected): void
    {
        $scope = $this->createTransformationScope();

        $bag = new PlaceholderBag();
        $bag->add('%foobar%', 'foo');

        $transformer = new PlaceholderArgumentTransformer($bag);

        static::assertEquals(
            $expected,
            $transformer->supportsDefinitionAndArgument($scope, 0, $actual)
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

    public function testTransformPyStringPreservesTypeAndLine(): void
    {
        $bag = new PlaceholderBag();
        $bag->add('%foobar%', 'foo');
        $transformer = new PlaceholderArgumentTransformer($bag);
        $scope = $this->createTransformationScope();
        $argument = new PyStringNode(['Hello %foobar%', '%foobar%'], 42);

        static::assertTrue($transformer->supportsDefinitionAndArgument($scope, 0, $argument));
        $result = $transformer->transformArgument($scope, 0, $argument);

        static::assertInstanceOf(PyStringNode::class, $result);
        static::assertSame("Hello foo\nfoo", $result->getRaw());
        static::assertSame(42, $result->getLine());
    }

    public function testTransformDocStringPreservesType(): void
    {
        $bag = new PlaceholderBag();
        $bag->add('%foobar%', 'foo');
        $transformer = new PlaceholderArgumentTransformer($bag);
        $scope = $this->createTransformationScope();
        $argument = new DocString(new PyStringNode(['Hello %foobar%', '%foobar%'], 42));

        static::assertTrue($transformer->supportsDefinitionAndArgument($scope, 'text', $argument));
        $result = $transformer->transformArgument($scope, 'text', $argument);

        static::assertInstanceOf(DocString::class, $result);
        static::assertSame("Hello foo\nfoo", $result->getContent());
    }

    #[DataProvider('dataUnsupportedArguments')]
    public function testUnsupportedArguments(mixed $argument): void
    {
        $bag = new PlaceholderBag();
        $bag->add('%foobar%', 'foo');
        $transformer = new PlaceholderArgumentTransformer($bag);

        static::assertFalse($transformer->supportsDefinitionAndArgument($this->createTransformationScope(), 0, $argument));
    }

    public static function dataUnsupportedArguments(): array
    {
        return [
            [null],
            [123],
            [false],
            [['%foobar%']],
            [new TableNode([['%foobar%']])],
            [new PyStringNode(['%unknown%'], 1)],
            [new DocString(new PyStringNode(['%unknown%'], 1))],
        ];
    }

    private function createTransformationScope(): TransformationScope
    {
        return $this->createStub(TransformationScope::class);
    }
}
