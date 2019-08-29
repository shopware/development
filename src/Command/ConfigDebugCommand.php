<?php declare(strict_types=1);

namespace Shopware\Development\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Container;

class ConfigDebugCommand extends Command
{
    /**
     * @var Container
     */
    private $container;

    public function __construct(Container $container)
    {
        parent::__construct();
        $this->container = $container;
    }

    protected function configure(): void
    {
        $this->setName('shopware:debug:config');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $command = $this->getApplication()->find('debug:config');

        $bundles = $this->container->get('kernel')->getBundles();

        $names = array_keys($bundles);

        foreach ($names as $name) {
            $arguments = [
                'command' => 'debug:config',
                'name'    => $name
            ];

            $greetInput = new ArrayInput($arguments);

            /** @var Command $command */
            try {
                $command->run($greetInput, $output);
            } catch (\Exception $e) {
                $output->writeln($e->getMessage());
            }
        }
    }
}
