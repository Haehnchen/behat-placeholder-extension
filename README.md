# Behat Placeholder Extension

[![Build Status](https://travis-ci.org/Haehnchen/behat-placeholder-extension.svg?branch=master)](https://travis-ci.org/Haehnchen/behat-placeholder-extension)
[![Total Downloads](https://poser.pugx.org/espend/behat-placeholder-extension/downloads.png)](https://packagist.org/packages/espend/behat-placeholder-extension)
[![Latest Stable Version](https://poser.pugx.org/espend/behat-placeholder-extension/v/stable.png)](https://packagist.org/packages/espend/behat-placeholder-extension)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/bb3569b9-9c7c-48ce-97ea-91a4adf87c9c/mini.png)](https://insight.sensiolabs.com/projects/bb3569b9-9c7c-48ce-97ea-91a4adf87c9c)
[![Build Status](https://scrutinizer-ci.com/g/Haehnchen/behat-placeholder-extension/badges/build.png?b=master)](https://scrutinizer-ci.com/g/Haehnchen/behat-placeholder-extension/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Haehnchen/behat-placeholder-extension/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Haehnchen/behat-placeholder-extension/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/Haehnchen/behat-placeholder-extension/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Haehnchen/behat-placeholder-extension/?branch=master)

## Problem to solve


If you test your application with external service you run into problems of non unique user input.
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

Also extracting an given value of newly generate user is possible.

```
Scenario: Register a new user and check id
    [...]
    When set placeholder "%user_id%" of "id" on Doctrine model "AppBundle:User" with "email" equals "%email%"
    Then print placeholder value of "%user_id%"    
    Then I should see "%user_id%" in the ".account-user-id" element
```

All placeholder are compatible with foreign `Context` arguments.

## Installation

``` bash
$ composer require espend/behat-placeholder-extension
```

```yaml
# behat.yaml

default:
  suites:
    default:
      contexts:
        - espend\Behat\PlaceholderExtension\Context\PlaceholderContext
  
  extensions:
    espend\Behat\PlaceholderExtension\PlaceholderExtension: ~
```

### Feature Steps

All placeholder are valid per Scenario scope. They are cleaned before and after every Scenario.

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

To interact with the underlying Database there also some Doctrine steps.
They only work on a Test Suite / Extension using `Behat\Symfony2Extension`

```
Given set placeholder "%foobar%" of "id" on Doctrine model "AppBundle:Car" with "name" equals "bmw"
Given set placeholder "%foobar%" of "id" on Doctrine model "AppBundle\Entity\Car" with "name" equals "bmw"
```

```yaml
# behat.yaml
default:
  suites:
    default:
      contexts:
        - espend\Behat\PlaceholderExtension\Context\DoctrinePlaceholderContext

  extensions:
    # [...]
    Behat\Symfony2Extension: ~
    Behat\MinkExtension:
      sessions:
        default:
          symfony2: ~
```

```
# composer.json
"behat/mink-extension": "*",
"behat/symfony2-extension": "*",
```

#### Placeholder Context Injection

If you want access to placeholders in you custom `Context` you implement the `espend\Behat\PlaceholderExtension\Context\PlaceholderBagAwareContextInterface` Interface
See `PlaceholderContext` for a full working example

```
class PlaceholderContext implements Context, PlaceholderBagAwareContext {}
```

## TODOs

 - Pipe placeholder arguments for console command: `bin/behat --placeholder="%foobar%=foo"`
 - More Doctrine related steps
 - More Placeholder specific steps

 
