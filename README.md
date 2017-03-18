# Behat Placeholder Extension

[![Build Status](https://travis-ci.org/Haehnchen/behat-placeholder-extension.svg?branch=master)](https://travis-ci.org/Haehnchen/behat-placeholder-extension)
[![Total Downloads](https://poser.pugx.org/espend/behat-placeholder-extension/downloads.png)](https://packagist.org/packages/espend/behat-placeholder-extension)
[![Latest Stable Version](https://poser.pugx.org/espend/behat-placeholder-extension/v/stable.png)](https://packagist.org/packages/espend/behat-placeholder-extension)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/bb3569b9-9c7c-48ce-97ea-91a4adf87c9c/mini.png)](https://insight.sensiolabs.com/projects/bb3569b9-9c7c-48ce-97ea-91a4adf87c9c)
[![Build Status](https://scrutinizer-ci.com/g/Haehnchen/behat-placeholder-extension/badges/build.png?b=master)](https://scrutinizer-ci.com/g/Haehnchen/behat-placeholder-extension/build-status/master)

## Installation

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

```
    Given I set a random mail in "%mail%" placeholder
    Given I set a random password in "%password%" placeholder
    Given I set a random text with length "15" in "%text%" placeholder
    Given I set current date as "Y-m-d" format in "%date%" placeholder
    Given print placeholder value of "%date%"
    Given print all placeholder values
```

#### Doctrine

```
Given I set a placeholder "%foobar%" on Doctrine model "MyUser" with "id=13" and "name"
```

#### Placeholder Context Injection

`espend\Behat\PlaceholderExtension\Context\PlaceholderBagAwareContext`

```
class PlaceholderContext implements Context, PlaceholderBagAwareContext {}
```