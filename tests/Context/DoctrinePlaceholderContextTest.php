<?php
declare(strict_types = 1);

namespace espend\Behat\PlaceholderExtension\Tests\Context;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use espend\Behat\PlaceholderExtension\Context\DoctrinePlaceholderContext;
use espend\Behat\PlaceholderExtension\PlaceholderBag;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * @author Daniel Espendiller <daniel@espendiller.net>
 */
class DoctrinePlaceholderContextTest extends TestCase
{
    public function testThatInvalidPlaceMustThrowException()
    {
        $this->expectException(\RuntimeException::class);
        $context = new DoctrinePlaceholderContext();
        $context->setPlaceholderOfPropertyOnDoctrineModelWithCriteriaAndProperty('foo%', 'foo', 'foo', 'foo', 'foo');
    }

    public function testInvalidClassForManagerMustThrowException()
    {
        $this->expectException(\RuntimeException::class);
        $manager = $this->createMock(ManagerRegistry::class);
        $manager->method('getManagerForClass')->willReturn(null);

        $container = new Container();
        $container->set('doctrine', $manager);

        $context = $this->createContextWithContainer($container, new PlaceholderBag());

        $context->setPlaceholderOfPropertyOnDoctrineModelWithCriteriaAndProperty('foo%', 'foo', 'foo', 'foo', 'foo');
    }

    public function testThatPropertyResolvesValue()
    {
        $container = $this->createRepositoryContainerWithReturn(new class
        {
            public function getName()
            {
                return 'my_name';
            }
        });

        $bag = new PlaceholderBag();

        $context = $this->createContextWithContainer($container, $bag);
        $context->setPlaceholderOfPropertyOnDoctrineModelWithCriteriaAndProperty(
            '%foo%',
            'name',
            'Datetime',
            'name',
            'foobar'
        );

        static::assertEquals('my_name', $bag->all()['%foo%']);
    }

    public function testThatInvalidPropertyAccessValueMustThrowException()
    {
        $this->expectException(\PHPUnit\Framework\AssertionFailedError::class);
        $container = $this->createRepositoryContainerWithReturn(new class
        {
            public function getName()
            {
                return 'my_name';
            }
        });

        $context = $this->createContextWithContainer($container);
        $context->setPlaceholderOfPropertyOnDoctrineModelWithCriteriaAndProperty(
            '%foo%',
            'foobar',
            'Datetime',
            'name',
            'foobar'
        );
    }

    public function testThatFindOneByReturnsNull()
    {
        $this->expectException(\PHPUnit\Framework\AssertionFailedError::class);
        $container = $this->createRepositoryContainerWithReturn(null);

        $context = $this->createContextWithContainer($container);
        $context->setPlaceholderOfPropertyOnDoctrineModelWithCriteriaAndProperty(
            '%foo%',
            'foobar',
            'Datetime',
            'name',
            'foobar'
        );
    }

    /**
     * @param ContainerInterface $container
     * @param PlaceholderBag $bag
     * @return DoctrinePlaceholderContext
     */
    private function createContextWithContainer(
        ContainerInterface $container,
        PlaceholderBag $bag = null
    ): DoctrinePlaceholderContext {
        $kernel = $this->createMock(KernelInterface::class);
        $kernel->method('getContainer')->willReturn($container);

        $context = new DoctrinePlaceholderContext();
        $context->setKernel($kernel);
        $context->setPlaceholderBag($bag ?? new PlaceholderBag());

        return $context;
    }

    /**
     * @param mixed $return
     * @return Container
     */
    private function createRepositoryContainerWithReturn($return): Container
    {
        $repository = $this->createMock(ObjectRepository::class);

        $repository
            ->method('findOneBy')
            ->with(['name' => 'foobar'])
            ->willReturn($return);

        $objectManager = $this->createMock(ObjectManager::class);
        $objectManager->method('getRepository')->willReturn($repository);

        $manager = $this->createMock(ManagerRegistry::class);
        $manager->method('getManagerForClass')->willReturn($objectManager);

        $container = new Container();
        $container->set('doctrine', $manager);

        return $container;
    }
}
