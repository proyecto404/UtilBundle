<?php

namespace Proyecto404\UtilBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;

/**
 * Class RunTestCommand
 */
class RunTestCommand extends ContainerAwareCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('proyecto404:run-test')
            ->setDescription('Run project tests')
            ->addArgument('test', InputArgument::REQUIRED, 'Test class');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $testClass = $input->getArgument('test');
        
        $php = escapeshellarg(static::getPhp(false));
        $phpArgs = implode(' ', array_map('escapeshellarg', static::getPhpArguments()));
        $timeout = 300;
        $phpUnit = 'vendor/phpunit/phpunit/phpunit '.$testClass;
        $process = new Process($php.($phpArgs ? ' '.$phpArgs : '').' '.$phpUnit, null, null, null, $timeout);
        $process->run(function ($type, $buffer) use ($output) {
            $output->write($buffer, false);
        });
        if (!$process->isSuccessful()) {
            throw new \RuntimeException(sprintf('An error occurred when executing the "%s" command.', escapeshellarg($phpUnit)));
        }
    }

    protected static function getPhp($includeArgs = true)
    {
        $phpFinder = new PhpExecutableFinder();
        if (!$phpPath = $phpFinder->find($includeArgs)) {
            throw new \RuntimeException('The php executable could not be found, add it to your PATH environment variable and try again');
        }

        return $phpPath;
    }

    protected static function getPhpArguments()
    {
        $arguments = array();

        $phpFinder = new PhpExecutableFinder();
        if (method_exists($phpFinder, 'findArguments')) {
            $arguments = $phpFinder->findArguments();
        }

        if (false !== $ini = php_ini_loaded_file()) {
            $arguments[] = '--php-ini='.$ini;
        }

        return $arguments;
    }
}
