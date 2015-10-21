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
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Cilex\Command\Command;
use Localgod\Carpet\Provider\Jira\Jira;

/**
 * Get group information
 *
 * @category Console tool
 * @author Johannes Skov Frandsen <localgod@heaven.dk>
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @link https://github.com/localgod Carpet
 */
class GroupCommand extends Command
{

    /**
     * Jira connection
     *
     * @var Carpet\Provider\Jira\Jira
     */
    private $jira;

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
        $this->setName('group')->setDescription('Get info about a group');
        $this->addArgument('groupname', InputArgument::OPTIONAL, 'What group are you looking for?');
        $this->addOption('members', null, InputOption::VALUE_NONE, 'List members');
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
        $name = $input->getArgument('groupname');
        
        if ($name) {
            $data = $this->jira->group($name);
        } else {
            $data = $this->jira->group();
        }
        
        $output->writeln('');
        if (isset($data['name'])) {
            $output->writeln('<info>' . str_pad('Group Name', 20) . str_pad('Members', 20) . '</info>');
            $output->writeln(str_repeat('_', 50));
            $output->writeln('<info>' . str_pad($data['name'], 20) . str_pad($data['users']['size'], 20) . '</info>');
            $output->writeln(str_repeat('', 50));
            
            if ($input->getOption('members')) {
                $output->writeln('<info>' . str_pad('', 5) . str_pad('G-Number', 20) . str_pad('Name', 20) . '</info>');
                $output->writeln(str_repeat('_', 50));
                $count = 1;
                foreach ($data['users']['items'] as $user) {
                    $lineNumber = strlen($count) == 1 ? '0' . $count : $count;
                    $output->writeln(
                        '<info>' .
                        str_pad($lineNumber, 5) .
                        str_pad($user['name'], 20) .
                        str_pad($user['displayName'], 20) . '</info>'
                    );
                    $count ++;
                }
            }
        } else {
            $output->writeln('<comment>No group found</comment>');
        }
    }
}
