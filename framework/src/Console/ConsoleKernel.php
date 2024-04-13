<?php

namespace Igarevv\Micrame\Console;

use Igarevv\Micrame\Console\Commands\CommandInterface;
use Igarevv\Micrame\Console\Exceptions\NullCommandException;
use Psr\Container\ContainerInterface;

class ConsoleKernel
{

    private ?string $commandType;

    public function __construct(
      private ContainerInterface $container,
      private ConsoleApplication $application
    ) {}

    public function handle(): int
    {
        try {
            $this->registerSystemCommands();

            $status = $this->application->run();
        } catch (\Throwable $e) {
            echo $e->getMessage();
            return 1;
        }
        return $status;
    }

    private function registerSystemCommands(): void
    {
        $directoryIterator = new \DirectoryIterator(__DIR__.'/Commands');

        $namespace = 'Igarevv\\Micrame\\Console\\Commands\\';

        $this->commandType = $_SERVER['argv'][1] ?? null;

        if ( ! $this->commandType) {
            throw new NullCommandException('Command line required type and action');
        }

        /** @var \DirectoryIterator $file */
        foreach ($directoryIterator as $file) {
            if ( ! $file->isFile()) {
                continue;
            }

            $commandNamespace = $namespace.pathinfo($file->getFilename(),
                PATHINFO_FILENAME);

            if (is_subclass_of($commandNamespace, CommandInterface::class)) {
                $value = (new \ReflectionClass($commandNamespace))
                  ->getProperty('action')
                  ->getDefaultValue();

                $this->container->add("{$this->commandType}:{$value}",
                  $commandNamespace);
            }
        }
    }

}