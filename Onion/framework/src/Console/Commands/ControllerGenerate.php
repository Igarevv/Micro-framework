<?php

namespace Igarevv\Micrame\Console\Commands;

use Igarevv\Micrame\Console\Exceptions\GeneratorException;

class ControllerGenerate implements CommandInterface
{

    private string $type = 'controller';

    private string $action = 'generate';

    private array $config;

    public function __construct()
    {
        $this->config = require APP_PATH.'/config/app.php';
    }

    public function execute(array $params = []): int
    {
        $path = $this->config['controller']['path'];

        $className = preg_replace('/\.[^.]*/', '', $params['name']);

        $content = $this->defaultContent($className);

        $fullPath = APP_PATH.$path.'/'.$className.'.php';

        if (file_exists($fullPath)) {
            throw new GeneratorException('File with this name is already exists');
        }

        file_put_contents($fullPath, $content);

        echo "Controller created successfully".PHP_EOL;

        return 0;
    }

    private function defaultContent(string $className): string
    {
        $namespace = $this->config['controller']['namespace'];

        $content = "<?php\n\n";
        $content .= "namespace {$namespace};\n\n";
        $content .= "use Igarevv\Micrame\Controller\Controller;\n\n";
        $content .= "class {$className} extends Controller\n";
        $content .= "{\n\n";
        $content .= "   // Your code here\n\n";
        $content .= "}\n";

        return $content;
    }

}