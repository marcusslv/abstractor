<?php

namespace Codehubmvs\Abstracts\Commands;

use Illuminate\Console\Command;

class GenerateAbstracts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:abstracts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate abstract classes for services, repositories, controllers and models.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $packageName = $this->askForPackageName();

        $this->info("Generating abstract classes for {$packageName} package.");
        $this->ensurePackageExists($packageName);
        $this->ensureAbstractsDirectoryExists($packageName);
        $this->serviceInterface($packageName);
        $this->service($packageName);
        $this->repositoryInterface($packageName);
        $this->repository($packageName);
        $this->controller();
        $this->baseController();
        $this->info('Abstract classes generated successfully.');
    }

    private function askForPackageName(): string
    {
        return $this->ask('What is the package name?');
    }

    private function ensurePackageExists(string $packageName): void
    {
        if (! is_dir(app_path($packageName))) {
            $this->createPackageIfConfirmed($packageName);
        }
    }

    private function ensureAbstractsDirectoryExists(string $packageName): void
    {
        $path = app_path($packageName.'/'.'Abstracts');
        if (! file_exists($path)) {
            mkdir($path);
        }
    }

    private function createPackageIfConfirmed(string $packageName): void
    {
        $result = $this->ask("Package {$packageName} does not exist. Do you want to create it? (y/n)");
        if ($result === 'n') {
            dd('No package found. Exiting.');
        }

        mkdir(app_path($packageName));
    }

    private function template(string $templateName): string
    {
        $templateFiles = glob(base_path("/vendor/**/**/**/**/{$templateName}.txt"));

        if (empty($templateFiles)) {
            dd("Template file {$templateName}.txt not found.");
        }

        return file_get_contents($templateFiles[0]);
    }

    private function createFileFromTemplate(
        string $root,
        string $templateName,
        string $destinationPath,
        string $destinationFileName
    ): void {
        $this->warn("Generating {$destinationFileName}.php...");
        $templateContent = $this->template($templateName);
        $namespace = 'App\\'.$root.'\\'.$destinationPath;
        $templateContent = str_replace('DUMMY_NAMESPACE', $namespace, $templateContent);

        $destinationFullPath = app_path("{$root}/{$destinationPath}/{$destinationFileName}.php");

        if (file_put_contents($destinationFullPath, $templateContent) === false) {
            dd("Failed to create {$destinationFileName}.php.");
        }
    }

    private function serviceInterface($root): void
    {
        $this->createFileFromTemplate($root, 'ServiceInterface', 'Abstracts', 'ServiceInterface');
        $this->info('Base service interface generated successfully.');
    }

    private function service($root): void
    {
        $this->createFileFromTemplate($root, 'AbstractService', 'Abstracts', 'AbstractService');
        $this->info('Base service generated successfully.');
    }

    private function repositoryInterface($root): void
    {
        $this->createFileFromTemplate($root, 'RepositoryInterface', 'Abstracts', 'RepositoryInterface');
        $this->info('Base repository interface generated successfully.');
    }

    private function repository($root): void
    {
        $this->createFileFromTemplate($root, 'AbstractRepository', 'Abstracts', 'AbstractRepository');
        $this->info('Base repository generated successfully.');
    }

    private function controller(): void
    {
        $this->createFileFromTemplate('', 'AbstractController', 'Http/Controllers', 'AbstractController');
        $this->info('Base controller generated successfully.');
    }

    private function baseController(): void
    {
        $this->createFileFromTemplate('', 'Controller', 'Http/Controllers', 'Controller');
        $this->info('Base controller generated successfully.');
    }
}
