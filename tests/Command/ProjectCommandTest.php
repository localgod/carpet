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
use Localgod\Carpet\Command\ProjectCommand;
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
class ProjectCommandTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function testCommandWithNoProjects()
    {
        $application = new Application();
        
        $jira = $this->getMockBuilder('Carpet\Provider\Jira\Jira')
            ->disableOriginalConstructor()
            ->getMock();
        
        $jira->method('projects')->willReturn(array());
        $jira->method('project')->willReturn(array());
        
        $application->add(new ProjectCommand($jira));
        
        $command = $application->find('Project');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command' => $command->getName()
        ));
        
        $this->assertRegExp('/No projects found/', $commandTester->getDisplay());
    }

    /**
     * @test
     */
    public function testCommandWithOneProject()
    {
        $application = new Application();
        
        $jira = $this->getMockBuilder('Carpet\Provider\Jira\Jira')
            ->disableOriginalConstructor()
            ->getMock();
        $projects = json_decode('[{"key":"TEST","name":"Test","projectCategory":{"name":"Test"}}]', true);
        $project = json_decode('{"description":"test"}', true);
        $jira->method('projects')->willReturn($projects);
        $jira->method('project')->willReturn($project);
        $application->add(new ProjectCommand($jira));
        
        $command = $application->find('Project');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command' => $command->getName()
        ));
        
        $this->assertRegExp('/TEST\s*Test\s*Yes\s*Yes/', $commandTester->getDisplay());
    }
}
