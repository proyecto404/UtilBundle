<?php

namespace Proyecto404\UtilBundle\Command;


use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * {@inheritDoc}
 */
class GenerateDatabaseCommand extends ContainerAwareCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('proyecto404:generate-database')
            ->setDescription('Generate database and load it with fixtures');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->executeCommand('doctrine:database:drop', array('--force' => true), $output);

        $returnCode = $this->executeCommand('doctrine:database:create', array(), $output);
        if ($returnCode != 0) {
            return;
        }

        $returnCode = $this->executeCommand('doctrine:schema:update', array('--force' => true), $output);
        if ($returnCode != 0) {
            return;
        }

        $arguments = array('sql_file' => 'src\\DataBundle\\Scripts\\after_create_schema.sql');
        $returnCode = $this->executeCommand('proyecto404:execute-sql', $arguments, $output);
        if ($returnCode != 0) {
            return;
        }

        try {
            $arguments = array(
                '--no-interaction' => true,
                '--fixtures'       => array('src\\DataBundle\\DataFixtures\\Required')
            );
            $this->executeCommand('doctrine:fixtures:load', $arguments, $output);
        } catch (InvalidArgumentException $e) {
            $output->writeln('<comment>No required fixtures found</comment>');
        }

        try {
            $arguments = array(
                '--no-interaction' => true,
                '--fixtures' => array('..\\src\\DataBundle\\DataFixtures\\Development')
            );
            $this->executeCommand('doctrine:fixtures:load', $arguments, $output);
        } catch (InvalidArgumentException $e) {
            $output->writeln('<comment>No development fixtures found</comment>');
        }

        $arguments = array('sql_file' => '..\\src\\DataBundle\\Scripts\\after_data_load.sql');
        $returnCode = $this->executeCommand('proyecto404:execute-sql', $arguments, $output);
        if ($returnCode != 0) {
            return;
        }
    }

    private function executeCommand($command, $arguments, OutputInterface $output)
    {
        $command = $this->getApplication()->find($command);
        $arguments['command'] = $command;
        $input = new ArrayInput($arguments);
        $input->setInteractive(false);

        return $command->run($input, $output);
    }
}