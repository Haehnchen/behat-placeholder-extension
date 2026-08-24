# Behat Placeholder Extension

[![PHPUnit Tests](https://github.com/Haehnchen/behat-placeholder-extension/actions/workflows/phpunit.yml/badge.svg)](https://github.com/Haehnchen/behat-placeholder-extension/actions/workflows/phpunit.yml)
[![Total Downloads](https://poser.pugx.org/espend/behat-placeholder-extension/downloads.png)](https://packagist.org/packages/espend/behat-placeholder-extension)
[![Latest Stable Version](https://poser.pugx.org/espend/behat-placeholder-extension/v/stable.png)](https://packagist.org/packages/espend/behat-placeholder-extension)

## Version Compatibility

| Extension | Behat |
|-----------|-------|
| `2.x`     | `3.x` |
| `3.x`     | `4.x` |

## Problem to solve


If you test your application with external services, you can run into problems with non-unique user input.
For example registering a user with same email will fail as there is already a user inside your database with this email address.

```
Scenario: Register a new user
    Given I fill in "email" with "foo@exmaple.com"
    And I fill in "password" with "my_scret"
    And I press "Register Now"
    Then I should see "foo@exmaple.com" in the ".account-user" element

Scenario: Register a new user
    Given set a random mail in "%email%" placeholder
    And set a random password in "%password%" placeholder
    And I fill in "email" with "%email%"
    And I fill in "password" with "%password%"
    And I press "Register Now"
    Then I should see "%email%" in the ".account-user" element
    Then I should see "Hello %email%" in the ".account-user" element
```

Extracting a value from a newly generated user is also possible.

```
Scenario: Register a new user and check id
    [...]
    When set placeholder "%user_id%" of "id" on Doctrine model "AppBundle:User" with "email" equals "%email%"
    Then print placeholder value of "%user_id%"    
    Then I should see "%user_id%" in the ".account-user-id" element
```

All placeholders are compatible with arguments from other `Context` classes.

## Installation

```bash
composer require espend/behat-placeholder-extension
```

Version 3 requires PHP 8.2 or newer and supports Behat 4.

```php
<?php
// behat.php

use Behat\Config\Config;
use Behat\Config\Extension;
use Behat\Config\Profile;
use Behat\Config\Suite;
use espend\Behat\PlaceholderExtension\Context\PlaceholderContext;
use espend\Behat\PlaceholderExtension\PlaceholderExtension;

return (new Config())
    ->withProfile(
        (new Profile('default'))
            ->withSuite(
                (new Suite('default'))
                    ->withContexts(PlaceholderContext::class)
            )
            ->withExtension(new Extension(PlaceholderExtension::class))
    );
```

### Feature Steps

All placeholders are valid for one scenario. They are cleaned before and after every scenario.

```
Given set a placeholder "%foobar%" with value "my_foobar"
Given set a random mail in "%mail%" placeholder
Given set a random password in "%password%" placeholder
Given set a random text with length "15" in "%text%" placeholder
Given set current date as "Y-m-d" format in "%date%" placeholder
Given print placeholder value of "%date%"
Given print all placeholder values
```

#### Doctrine

The optional Doctrine context can read a value from an entity and store it as a placeholder. It uses constructor
injection and is intended to be registered as a service through the maintained Symfony extension.

```bash
composer require --dev friends-of-behat/symfony-extension doctrine/persistence symfony/property-access
```

```
Given set placeholder "%foobar%" of "id" on Doctrine model "AppBundle:Car" with "name" equals "bmw"
Given set placeholder "%foobar%" of "id" on Doctrine model "AppBundle\Entity\Car" with "name" equals "bmw"
```

```php
<?php
// behat.php

use Behat\Config\Config;
use Behat\Config\Extension;
use Behat\Config\Profile;
use Behat\Config\Suite;
use espend\Behat\PlaceholderExtension\Context\DoctrinePlaceholderContext;
use espend\Behat\PlaceholderExtension\PlaceholderExtension;
use FriendsOfBehat\SymfonyExtension\ServiceContainer\SymfonyExtension;

return (new Config())
    ->withProfile(
        (new Profile('default'))
            ->withSuite(
                (new Suite('default'))
                    ->withContexts(DoctrinePlaceholderContext::class)
            )
            ->withExtension(new Extension(PlaceholderExtension::class))
            ->withExtension(new Extension(SymfonyExtension::class))
    );
```

```yaml
# config/services_test.yaml
services:
  espend\Behat\PlaceholderExtension\Context\DoctrinePlaceholderContext:
    autowire: true
    autoconfigure: true
    public: true
```

#### Placeholder Context Injection

To access placeholders from a custom context, implement
`espend\Behat\PlaceholderExtension\Context\PlaceholderBagAwareContextInterface`. Behat 4 no longer discovers PHPDoc
step annotations, so context steps should use native PHP attributes:

```php
use Behat\Behat\Context\Context;
use Behat\Step\Given;
use espend\Behat\PlaceholderExtension\Context\PlaceholderBagAwareContextInterface;
use espend\Behat\PlaceholderExtension\PlaceholderBagInterface;

final class FeatureContext implements Context, PlaceholderBagAwareContextInterface
{
    private PlaceholderBagInterface $placeholders;

    public function setPlaceholderBag(PlaceholderBagInterface $placeholderBag): void
    {
        $this->placeholders = $placeholderBag;
    }

    #[Given('a custom placeholder step')]
    public function customPlaceholderStep(): void
    {
        $this->placeholders->add('%custom%', 'value');
    }
}
```

## TODOs

 - Pipe placeholder arguments for console command: `bin/behat --placeholder="%foobar%=foo"`
 - More Doctrine related steps
 - More Placeholder specific steps

 
