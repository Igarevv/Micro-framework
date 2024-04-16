<?php

namespace Igarevv\Micrame\Console;

use Igarevv\Micrame\Console\Commands\CommandInterface;
use Igarevv\Micrame\Console\Exceptions\NullCommandException;
use Psr\Container\ContainerInterface;

class ConsoleKernel
{

    public function __construct(
      private ContainerInterface $container,
      private ConsoleApplication $application
    ) {}

    public function handle(): int
    {

        try {
            $this->registerSystemCommands();

            $status = $this->application->run();
        } catch (\Throwable|\Exception $e) {
            echo $e->getMessage();
            return 1;
        }
        return $status;
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
                $reflection = new \ReflectionClass($commandNamespace);

                $type = $reflection->getProperty('type')->getDefaultValue();
                $action = $reflection->getProperty('action')->getDefaultValue();

                $this->container->add("{$type}:{$action}",
                  $commandNamespace);
            }
        }
    }

}