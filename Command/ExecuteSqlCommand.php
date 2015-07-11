<?php
namespace Proyecto404\UtilBundle\Command;

use Exception;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ExecuteSqlCommand
 */
class ExecuteSqlCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('proyecto404:execute-sql')
            ->setDescription('Execute sql file in the main database')
            ->addArgument('sql_file', InputArgument::REQUIRED, 'What sql file do you want to execute?');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sqlFile = $input->getArgument('sql_file');
        if (!file_exists($sqlFile)) {
            die('File '.$sqlFile.' not found');
        }
        $output->writeln('Executing '.$sqlFile.'...');
        $sql = file_get_contents($sqlFile);
        if (strlen(trim($sql)) == 0) {
            return;
        }

        $em = $this->getContainer()->get('doctrine')->getManager('default');
        $conn = $em->getConnection();

        try {
            $conn->beginTransaction();

            $conn->exec($sql);

            $conn->commit();
        } catch (Exception  $e) {
            $conn->rollback();

            throw $e;
        }
    }
}
