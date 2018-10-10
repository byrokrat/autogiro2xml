<?php

declare(strict_types = 1);

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Tester\Exception\PendingException;

class FeatureContext implements Context
{
    private const A_VALID_AUTOGIRO_FILE = <<<EOF
01AUTOGIRO              20170817            AG-MEDAVI           1234560050501055
092017081799000000000
EOF;

    private const AN_AUTOGIRO_FILE_WITH_INVALID_IDS = <<<EOF
0120080611AUTOGIRO                                            4711170009912346
04000991234600000000000001013300001212121212999999999998
EOF;

    /**
     * @var ApplicationWrapper
     */
    private $app;

    /**
     * @var string
     */
    private $executable;

    /**
     * @var Result Result from the last app invocation
     */
    private $result;

    public function __construct(string $executable)
    {
        $this->executable = $executable;
    }

    /**
     * @Given a fresh installation
     */
    public function aFreshInstallation(): void
    {
        $this->app = new ApplicationWrapper($this->executable);
    }

    /**
     * @Given a valid autogiro file named :name
     */
    public function aValidAutogiroFileNamed($name): void
    {
        $this->app->createFile((string)$name, self::A_VALID_AUTOGIRO_FILE);
    }

    /**
     * @Given a broken autogiro file named :name
     */
    public function aBrokenAutogiroFileNamed($name): void
    {
        $this->app->createFile((string)$name, 'this-is-not-a-valid-autogiro-file');
    }

   /**
    * @Given an autogiro file with invalid ids named :name
    */
   public function anAutogiroFileWithInvalidIdsNamed($name)
   {
       $this->app->createFile((string)$name, self::AN_AUTOGIRO_FILE_WITH_INVALID_IDS);
   }

    /**
     * @When I run :command
     */
    public function iRun($command): void
    {
        $this->result = $this->app->execute($command);
    }

    /**
     * @Then there is no error
     */
    public function thereIsNoError(): void
    {
        if ($this->result->isError()) {
            throw new \Exception("Error: {$this->result->getErrorOutput()}");
        }
    }

    /**
     * @Then I get an error
     */
    public function iGetAnError(): void
    {
        if (!$this->result->isError()) {
            throw new \Exception('App invocation should result in an error');
        }
    }

    /**
     * @Then I get an error like :regexp
     */
    public function iGetAnErrorLike($regexp): void
    {
        $this->iGetAnError();
        if (!preg_match($regexp, $this->result->getErrorOutput())) {
            throw new \Exception("Unable to find $regexp in error {$this->result->getErrorOutput()}");
        }
    }

    /**
     * @Then the output contains :expectedCount lines like :regexp
     */
    public function theOutputContainsLinesLike($expectedCount, $regexp): void
    {
        $output = explode("\n", $this->result->getOutput());

        $iCount = 0;

        foreach ($output as $line) {
            if (preg_match($regexp, $line)) {
                $iCount++;
            }
        }

        if ($iCount !== (int)$expectedCount) {
            throw new \Exception(
                "Invalid count ($iCount) of $regexp (expected $expectedCount) in {$this->result->getOutput()}"
            );
        }
    }

    /**
     * @Then the output contains :string
     */
    public function theOutputContains($string): void
    {
        if (!preg_match("/$string/i", $this->result->getOutput())) {
            throw new \Exception("Unable to find $string in output {$this->result->getOutput()}");
        }
    }

    /**
     * @Then the output does not contain :string
     */
    public function theOutputDoesNotContain($string): void
    {
        if (preg_match("/$string/i", $this->result->getOutput())) {
            throw new \Exception("$string should not be in output");
        }
    }
}
