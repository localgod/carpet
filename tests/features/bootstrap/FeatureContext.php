<?php
/**
 * Carpet
 *
 * PHP Version 5.5.0<
 *
 * @category Console tool
 * @author   Johannes Skov Frandsen <localgod@heaven.dk>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/localgod Carpet
 * @since    2014-07-29
 */
use Behat\Behat\Context\ClosuredContextInterface;
use Behat\Behat\Context\TranslatedContextInterface;
use Behat\Behat\Context\BehatContext;
use Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

/**
 * Features context.
 *
 * @category Console tool
 * @author Johannes Skov Frandsen <localgod@heaven.dk>
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @link https://github.com/localgod Carpet
 */
class FeatureContext extends BehatContext
{

    private $output;

    /**
     * Initializes context.
     *
     * Every scenario gets it's own context object. *
     *
     * @param array $parameters
     *            * context parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
        // Initialize your context here }
    }

    /**
     * @Given /^I can access Carpet$/
     */
    public function iCanAccessCarpet()
    {
        if (! file_exists('bin/Carpet.php')) {
            throw new Exception('The Carpet program was not found');
        }
    }

    /**
     * @When /^I perform the Project operation$/
     */
    public function iPerformTheProjectOperation()
    {
        exec('bin/Carpet.php Project', $out);
        $this->output = $out;
        if (! preg_match('/Key/', $out[1])) {
            throw new Exception('There was no output');
        }
    }

    /**
     * @Then /^I should get a list of projects$/
     */
    public function iShouldGetALsitOfProjects()
    {
        if (count($this->output) < 2) {
            throw new Exception('There was no projects');
        }
    }

    /**
     * @When /^I perform the group operation$/
     */
    public function iPerformTheGroupOperation()
    {
        exec('bin/Carpet.php group', $out);
        $this->output = $out;
        if (! preg_match('/Name/', $out[1])) {
            throw new Exception('There was no output');
        }
    }

    /**
     * @Then /^I should get info about the group$/
     */
    public function iShouldGetInfoAboutTheGroup()
    {
        if (count($this->output) < 2) {
            throw new Exception('There was no group');
        }
    }
}