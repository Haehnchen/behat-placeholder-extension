<?php
declare(strict_types = 1);

namespace espend\Behat\PlaceholderExtension\Tests\Context;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use espend\Behat\PlaceholderExtension\Context\DoctrinePlaceholderContext;
use espend\Behat\PlaceholderExtension\PlaceholderBag;
use PHPUnit\Framework\TestCase;

/**
 * @author Daniel Espendiller <daniel@espendiller.net>
 */
class DoctrinePlaceholderContextTest extends TestCase
{
    public function testThatInvalidPlaceMustThrowException(): void
    {
        $this->expectException(\RuntimeException::class);
        $context = $this->createContext($this->createStub(ManagerRegistry::class));
        $context->setPlaceholderOfPropertyOnDoctrineModelWithCriteriaAndProperty('foo%', 'foo', 'foo', 'foo', 'foo');
    }

    public function testInvalidClassForManagerMustThrowException(): void
    {
        $this->expectException(\RuntimeException::class);
        $manager = $this->createStub(ManagerRegistry::class);
        $manager->method('getManagerForClass')->willReturn(null);

        $context = $this->createContext($manager);

        $context->setPlaceholderOfPropertyOnDoctrineModelWithCriteriaAndProperty('foo%', 'foo', 'foo', 'foo', 'foo');
    }

    public function testThatPropertyResolvesValue(): void
    {
        $manager = $this->createManagerRegistryWithReturn(new class
        {
            public function getName()
            {
                return 'my_name';
            }
        });

        $bag = new PlaceholderBag();

        $context = $this->createContext($manager, $bag);
        $context->setPlaceholderOfPropertyOnDoctrineModelWithCriteriaAndProperty(
            '%foo%',
            'name',
            'Datetime',
            'name',
            'foobar'
        );

        static::assertEquals('my_name', $bag->all()['%foo%']);
    }

    public function testThatInvalidPropertyAccessValueMustThrowException(): void
    {
        $this->expectException(\RuntimeException::class);
        $manager = $this->createManagerRegistryWithReturn(new class
        {
            public function getName()
            {
                return 'my_name';
            }
        });

        $context = $this->createContext($manager);
        $context->setPlaceholderOfPropertyOnDoctrineModelWithCriteriaAndProperty(
            '%foo%',
            'foobar',
            'Datetime',
            'name',
            'foobar'
        );
    }

    public function testThatFindOneByReturnsNull(): void
    {
        $this->expectException(\RuntimeException::class);
        $manager = $this->createManagerRegistryWithReturn(null);

        $context = $this->createContext($manager);
        $context->setPlaceholderOfPropertyOnDoctrineModelWithCriteriaAndProperty(
            '%foo%',
            'foobar',
            'Datetime',
            'name',
            'foobar'
        );
    }

    /**
     * @param ManagerRegistry $managerRegistry
     */
    private function createContext(
        ManagerRegistry $managerRegistry,
        ?PlaceholderBag $bag = null
    ): DoctrinePlaceholderContext {
        $context = new DoctrinePlaceholderContext($managerRegistry);
        $context->setPlaceholderBag($bag ?? new PlaceholderBag());

        return $context;
    }

    private function createManagerRegistryWithReturn(mixed $return): ManagerRegistry
    {
        $repository = $this->createMock(ObjectRepository::class);

        $repository
            ->expects(static::once())
            ->method('findOneBy')
            ->with(['name' => 'foobar'])
            ->willReturn($return);

        $objectManager = $this->createStub(ObjectManager::class);
        $objectManager->method('getRepository')->willReturn($repository);

        $manager = $this->createStub(ManagerRegistry::class);
        $manager->method('getManagerForClass')->willReturn($objectManager);

        return $manager;
    }
}
