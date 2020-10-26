<?php

declare(strict_types=1);

namespace Shopware\Development\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class ConfigDebugCommand extends Command
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    public function __construct(KernelInterface $kernel)
    {
        parent::__construct();
        $this->kernel = $kernel;
    }

    protected function configure(): void
    {
        $this->setName('shopware:debug:config');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $application = $this->getApplication();
        if ($application === null) {
            throw new \RuntimeException('No Application initialised');
        }
        $command = $application->find('debug:config');

        $bundles = $this->kernel->getBundles();

        foreach (array_keys($bundles) as $name) {
            $arguments = [
                'command' => 'debug:config',
                'name' => $name,
            ];

            $greetInput = new ArrayInput($arguments);

            try {
                $command->run($greetInput, $output);
            } catch (\Exception $e) {
                $output->writeln($e->getMessage());
            }
        }

        return 0;
    }
}
