<?php
declare(strict_types = 1);

namespace espend\Behat\PlaceholderExtension\Context;

use Behat\Behat\Context\Context;
use espend\Behat\PlaceholderExtension\PlaceholderBagInterface;
use espend\Behat\PlaceholderExtension\Utils\PlaceholderUtil;
use PHPUnit\Framework\Assert as Assertions;

/**
 * @author Daniel Espendiller <daniel@espendiller.net>
 */
class PlaceholderContext implements Context, PlaceholderBagInterface
{
    /**
     * @var array
     */
    private $placeholders = [];

    /**
     * @var string
     */
    private $randomizedMail = 'behat-%random%@example.com';

    /**
     * Clear all context scenario placeholders
     *
     * @BeforeScenario
     * @AfterScenario
     */
    public function beforeAndAfterScenario()
    {
        $this->placeholders = [];
    }

    /**
     * @param string $placeholder
     * @param string $value
     * @Given /^I set a placeholder "([^"]*)" with value "([^"]*)"$/
     */
    public function iSetAPlaceholderWithValue(string $placeholder, string $value)
    {
        $this->validatePlaceholder($placeholder);

        $this->placeholders[$placeholder] = $value;
    }

    /**
     * @param string $placeholder
     * @Given /^I set a random mail in "([^"]*)" placeholder/
     */
    public function iCreateARandomMailPlaceholder(string $placeholder)
    {
        $this->iSetAPlaceholderWithValue(
            $placeholder,
            str_replace('%random%', substr(md5(uniqid()), 0, 6), $this->randomizedMail)
        );
    }

    /**
     * @param string $placeholder
     * @Given /^I set a random password in "([^"]*)" placeholder/
     */
    public function iCreateARandomPasswordPlaceholder(string $placeholder)
    {
        // some special cars
        $input = ['#', '/', '-', '~', '[', ']'];
        array_shift($input);

        $this->iSetAPlaceholderWithValue(
            $placeholder,
            substr(base64_encode(md5(uniqid())), 5, 15) . implode('', array_slice($input, 2))
        );
    }

    /**
     * @param string $format
     * @param string $placeholder
     * @Given /^I set current date as "([^"]*)" format in "([^"]*)" placeholder/
     */
    public function iSetCurrentDatetimeAsFormatInPlaceholder(string $format, string $placeholder)
    {
        $this->iSetAPlaceholderWithValue(
            $placeholder,
            (new \DateTime())->format($format)
        );
    }

    /**
     * @param string $length
     * @param string $placeholder
     * @Given /^I set a random text with length "(\d+)" in "([^"]*)" placeholder/
     */
    public function iSetARandomTextWithLengthInPlaceholder(string $length, string $placeholder)
    {
        Assertions::assertTrue(is_numeric($length), 'Invalid length given need integer');

        // randomized char whitelist a-z, A-Z, 0-9
        $randomChars = implode('', array_merge(range('a', 'z'), range('A', 'Z'), range(0, 9)));

        // randomize text with length
        $value = substr(str_shuffle(str_repeat($randomChars, mt_rand(1, 5))), 1, (int)$length);

        $this->iSetAPlaceholderWithValue($placeholder, $value);
    }

    /**
     * @param string $placeholder
     * @Given /^print placeholder value of "([^"]*)"/
     */
    public function printPlaceholderValueOf(string $placeholder)
    {
        echo sprintf('Placeholder "%s": "%s"', $placeholder, $this->placeholders[$placeholder] ?? 'not set');
    }

    /**
     * @Given /^print all placeholder values/
     */
    public function printAllPlaceholder()
    {
        foreach (array_keys($this->placeholders) as $key) {
            $this->printPlaceholderValueOf($key);
        }
    }

    /**
     * @param string $placeholder
     */
    private function validatePlaceholder(string $placeholder)
    {
        if (!PlaceholderUtil::isValidPlaceholder($placeholder)) {
            throw new \RuntimeException(
                'Invalid placeholder given; please wrap your place with a percent sign %foobar%'
            );
        }
    }

    /**
     * Get per scenario placeholders
     *
     * @return array
     */
    public function getPlaceholders(): array
    {
        return $this->placeholders;
    }

    /**
     * @param string $randomizedMail
     */
    public function setRandomizedMail(string $randomizedMail)
    {
        if (false === strpos($randomizedMail, '%random%')) {
            throw new \RuntimeException('Please provide a %random% placeholder');
        }

        $this->randomizedMail = $randomizedMail;
    }

    /**
     * {@inheritdoc}
     */
    public function addPlaceholder(string $placeholder, string $value)
    {
        $this->validatePlaceholder($placeholder);

        $this->placeholders[$placeholder] = $value;
    }
}
