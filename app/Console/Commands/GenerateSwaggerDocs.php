<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use OpenApi\Generator;

class GenerateSwaggerDocs extends Command
{
    protected $signature = 'swagger:generate';
    protected $description = 'Generate Swagger documentation';

    public function handle()
    {
        $openapi = Generator::scan([app_path()]);
        file_put_contents(storage_path('api-docs/swagger.json'), $openapi->toJson());
        $this->info('Swagger documentation generated successfully!');
    }
}
