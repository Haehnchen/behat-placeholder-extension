<?php
declare(strict_types = 1);

use Behat\Config\Config;
use Behat\Config\Extension;
use Behat\Config\Profile;
use Behat\Config\Suite;
use espend\Behat\PlaceholderExtension\Context\PlaceholderContext;
use espend\Behat\PlaceholderExtension\PlaceholderExtension;
use espend\Behat\PlaceholderExtension\Tests\Integration\FeatureContext;

return (new Config())
    ->withProfile(
        (new Profile('default'))
            ->withSuite(
                (new Suite('placeholder'))
                    ->withPaths('%paths.base%/features')
                    ->withContexts(PlaceholderContext::class, FeatureContext::class)
            )
            ->withExtension(new Extension(PlaceholderExtension::class))
    );
