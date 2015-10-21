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
namespace Localgod\Carpet\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Cilex\Command\Command;
use Localgod\Carpet\Provider\Jira\Jira;

/**
 * Get project information
 *
 * @category Console tool
 * @author Johannes Skov Frandsen <localgod@heaven.dk>
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @link https://github.com/localgod Carpet
 */
class ProjectCommand extends Command
{

    /**
     * Jira connection
     *
     * @var Carpet\Provider\Jira\Jira
     */
    private $jira;

    /**
     * List of projects
     *
     * @var array
     */
    private $projects;

    /**
     * Constructs a new project command
     *
     * @param Carpet\Provider\Jira\Jira $jira
     *
     * @return void
     */
    public function __construct(Jira $jira)
    {
        $this->jira = $jira;
        parent::__construct();
    }

    /**
     * Configure the command
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('Project')->setDescription('Get info about projects');
    }

    /**
     * Execute the command
     *
     * @param Symfony\Component\Console\Input\InputInterface $input
     * @param Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $data = $this->jira->projects();
        foreach ($data as $project) {
            $category = isset($project['projectCategory']) ? $project['projectCategory']['name'] : '';
            
            $this->projects[] = array(
                'key' => $project['key'],
                'name' => $project['name'],
                'category' => $category
            );
        }
        $output->writeln('');
        if (! empty($this->projects)) {
            $header = '<info>' . str_pad('Key', 12)
                               . str_pad('Name', 60)
                               . str_pad('Has category', 20)
                               . str_pad('Has description', 20)
                               . '</info>';
            $output->writeln($header);
            foreach ($this->projects as $project) {
                $data = $this->jira->project($project['key']);
                $hasDescription = $data['description'] != '' ? 'Yes' : str_pad('No', 3);
                $hasCategory = $project['category'] != '' ? 'Yes' : str_pad('No', 3);
                $entry = str_pad($project['key'], 12) .
                         str_pad($project['name'], 60) .
                         str_pad($hasCategory, 20) .
                         str_pad($hasDescription, 20);
                $output->writeln($entry);
            }
        } else {
            $output->writeln('<comment>No projects found</comment>');
        }
    }
}
