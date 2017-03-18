# Behat Placeholder Extension

[![Total Downloads](https://poser.pugx.org/espend/behat-placeholder-extension/downloads.png)](https://packagist.org/packages/espend/behat-placeholder-extension)
[![Latest Stable Version](https://poser.pugx.org/espend/behat-placeholder-extension/v/stable.png)](https://packagist.org/packages/espend/behat-placeholder-extension)

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