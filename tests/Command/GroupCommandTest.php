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
 * @since    2015-10-21
 */
use Localgod\Carpet\Command\GroupCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Test
 *
 * @category Console tool
 * @author Johannes Skov Frandsen <localgod@heaven.dk>
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @link https://github.com/localgod Carpet
 */
class GroupCommandTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function testCommandWithNonExistingGroup()
    {
        $application = new Application();
        
        $jira = $this->getMockBuilder('Carpet\Provider\Jira\Jira')
            ->disableOriginalConstructor()
            ->getMock();
        
        $jira->method('group')->willReturn(array());
        
        $application->add(new GroupCommand($jira));
        
        $command = $application->find('group');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command' => $command->getName()
        ));
        
        $this->assertRegExp('/No group found/', $commandTester->getDisplay());
    }
    
    /**
     * @test
     */
    public function testCommandWithExistingGroup()
    {
        $application = new Application();
    
        $jira = $this->getMockBuilder('Carpet\Provider\Jira\Jira')
        ->disableOriginalConstructor()
        ->getMock();
        
        $group = json_decode('{"name":"jira-users","users":{"size":5}}', true);
        $jira->method('group')->willReturn($group);
    
        $application->add(new GroupCommand($jira));
    
        $command = $application->find('group');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command' => $command->getName()
        ));

        $this->assertRegExp('/jira-users\s*5/', $commandTester->getDisplay());
    }
}
