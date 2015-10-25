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
class GenerateEntitiesCommand extends ContainerAwareCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('proyecto404:generate-entities')
            ->setDescription('Generate entities getters and setters');

    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$this->getContainer()->hasParameter('app_name')) {
            $output->writeln('<error>Missing parameter app_name in parameters.yml</error>');

            return;
        }

        $appName = $this->getContainer()->getParameter('app_name');

        $command = $this->getApplication()->find('doctrine:generate:entities');
        $arguments = array(
            'command' => 'doctrine:generate:entities',
            '--no-backup' => true,
            'name' => $appName.'/DomainBundle/Entity'
        );

        $input = new ArrayInput($arguments);
        $returnCode = $command->run($input, $output);
        if ($returnCode == 0) {
            $output->writeln('entities generated successfully');
        }
    }
}