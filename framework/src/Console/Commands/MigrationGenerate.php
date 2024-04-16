<?php

namespace Igarevv\Micrame\Console\Commands;

class MigrationGenerate implements CommandInterface
{

    private string $type = 'migration';

    private string $action = 'generate';

    private array $config;

    private string $content = '';

    public function __construct()
    {
        $this->config = require APP_PATH.'/config/app.php';
    }

    public function execute(array $params = []): int
    {
        $pathToMigrations = $this->config['migration'];
        $content = $this->generateContent();

        $time = (new \DateTime())->format('Y-m-d-H:i:s-T');
        $pathToSave = APP_PATH.$pathToMigrations."/$time.php";

        file_put_contents($pathToSave, $content);

        echo "Migration file generated successfully".PHP_EOL;
        return 0;
    }

    private function generateContent(): string
    {
        $this->content = "<?php\n\n";
        $this->content .= "use Doctrine\DBAL\Schema\Schema;\n\n";
        $this->content .= "return new class\n";
        $this->content .= "{\n";
        $this->generateMethod('up', ['Schema' => '$schema']);
        $this->generateMethod('down', ['Schema' => '$schema']);
        $this->content .= "};";

        return $this->content;
    }

    private function generateMethod(string $name, array $params = []): void
    {
        $paramsToString = $this->paramsArrayToString($params);
        $this->content .= "\t\tpublic function {$name}({$paramsToString}): void\n";
        $this->content .= "\t\t{\n";
        $this->content .= "\t\t\t// Customize your scheme here\n";
        $this->content .= "\t\t}\n\n";
    }

    private function paramsArrayToString(array $params): string
    {
        $stringParams = '';

        if ( ! $params) {
            return '';
        }

        if (count($params) === 1) {
            $stringParams = implode(', ', array_map(function ($key, $value) {
                return "$key $value, ";
            }, array_keys($params), $params));

            return rtrim($stringParams, ', ');
        }

        foreach ($params as $key => $param) {
            $stringParams .= "{$key} {$param}, ";
        }

        return rtrim($stringParams, ', ');
    }

}