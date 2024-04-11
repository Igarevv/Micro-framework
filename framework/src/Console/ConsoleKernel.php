<?php

namespace Igarevv\Micrame\Console;

use Igarevv\Micrame\Console\Commands\CommandInterface;
use Psr\Container\ContainerInterface;

class ConsoleKernel
{

    public function __construct(
      private ContainerInterface $container,
      private ConsoleApplication $application
    ) {}

    public function handle(): int
    {
        $this->registerSystemCommands();

        return $this->application->run();
    }

    private function registerSystemCommands(): void
    {
        $directoryIterator = new \DirectoryIterator(__DIR__.'/Commands');

        $namespace = 'Igarevv\\Micrame\\Console\\Commands\\';

        /** @var \DirectoryIterator $file */
        foreach ($directoryIterator as $file) {
            if ( ! $file->isFile()) {
                continue;
            }
            $commandNamespace = $namespace.pathinfo($file->getFilename(),
                PATHINFO_FILENAME);

            if (is_subclass_of($commandNamespace, CommandInterface::class)) {
                $value = (new \ReflectionClass($commandNamespace))
                  ->getProperty('name')
                  ->getDefaultValue();

                $this->container->add($value, $commandNamespace);
            }
        }
    }

}