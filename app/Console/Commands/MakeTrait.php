<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeTrait extends Command
{
    protected $signature = 'make:trait {module} {name}';
    protected $description = 'Create a new trait inside a module';

    public function handle() {
        $module = ucfirst($this->argument('module'));
        $name = ucfirst($this->argument('name'));
        $path = base_path("Modules/{$module}/app/Traits/{$name}.php");

        if (file_exists($path)) {
            $this->error("Trait {$name} already exists!");
            return;
        }

        (new Filesystem)->ensureDirectoryExists(dirname($path));

        file_put_contents($path, "<?php

        namespace Modules\\{$module}\\Traits;

        trait {$name} {
            // Define trait methods here
        }");

        $this->info("Trait {$name} created successfully in module {$module}!");
    }

}
