<?php

namespace Proyecto404\UtilBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * {@inheritDoc}
 */
class GenerateEntityCommand extends ContainerAwareCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('proyecto404:generate-entity')
            ->setDescription('Generate entity getters and setters')
            ->addArgument('entity', InputArgument::REQUIRED, 'Entity name');

    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entity = $input->getArgument('entity');
        $command = $this->getApplication()->find('doctrine:generate:entities');
        $arguments = array(
            'command' => 'doctrine:generate:entities',
            '--no-backup' => true,
            'name' => 'DomainBundle/Entity/'.$entity
        );

        $input = new ArrayInput($arguments);
        $returnCode = $command->run($input, $output);
        if ($returnCode == 0) {
            $output->writeln('entity generated successfully');
        }
    }
}