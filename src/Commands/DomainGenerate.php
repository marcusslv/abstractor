<?php

namespace Braip\Abstracts\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class DomainGenerate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:domain';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate domain classes.';

    public function handle(): void
    {
        $root = $this->ask('Info the root package name?');

        $domain = $this->ask('Info the domain name?');

        $this->warn("** Generating domain classes for {$domain} domain **");

        $this->ensurePackageExists($root.'/'.ucfirst($domain).'/Repositories', 0755, true);
        $this->ensurePackageExists($root.'/'.ucfirst($domain));
        $this->ensurePackageExists($root.'/'.ucfirst($domain).'/Entities');
        $this->ensurePackageExists($root.'/'.ucfirst($domain).'/Repositories');
        $this->ensurePackageExists($root.'/'.ucfirst($domain).'/Services');
        $this->ensurePackageExists($root.'/'.ucfirst($domain).'/ValueObjects');
        $this->entity($domain, $root);
        $this->repository($domain, $root);
        $this->service($domain, $root);
        $this->controller($domain, $root);
        $this->runInfraStructure($domain);
        $this->info('** Domain classes generated successfully. **');
    }

    private function entity($domain, $root): void
    {
        $this->warn('Generating entity for '.$domain.' domain.');
        $class = ucfirst($domain.'Entity');
        $content = $this->getTemplate('Entity');
        $content = str_replace(
            [
                'DUMMY_NAMESPACE',
                'DUMMY_ABSTRACT_ENTITY',
                'DUMMY_CLASS',
            ],
            [
                "App\\$root\\$domain\\Entities",
                "App\\$root\\Abstracts\AbstractEntity",
                $class,
            ],
            $content
        );

        $pathFile = app_path($root.'/'.$domain.'/Entities'.'/'.ucfirst($domain).'Entity.php');

        $this->createFileAndWrite($pathFile, $content);
        $this->info('Entity generated successfully.');
    }

    private function repository($domain, $root): void
    {
        $this->warn('Generating repository for '.$domain.' domain.');
        $class = ucfirst($domain.'Repository');
        $entity = ucfirst($domain).'Entity';

        $content = $this->getTemplate('Repository');
        $content = str_replace(
            [
                'DUMMY_NAMESPACE',
                'DUMMY_ENTITY',
                'DUMMY_REPOSITORY',
                'DUMMY_CLASS_ENTITY',
                'DUMMY_CLASS',
            ],
            [
                "App\\$root\\$domain\\Repositories",
                "App\\$root\\$domain\\Entities\\$entity",
                "App\\$root\\Abstracts\AbstractRepository",
                $entity,
                $class,
            ],
            $content
        );

        $pathFile = app_path($root.'/'.$domain.'/Repositories'.'/'.ucfirst($domain).'Repository.php');

        $this->createFileAndWrite($pathFile, $content);
        $this->info('Repository generated successfully.');
    }

    private function service($domain, $root): void
    {
        $this->warn('Generating service for '.$domain.' domain.');
        $classNameRepository = ucfirst($domain).'Repository';
        $content = $this->getTemplate('Service');
        $content = str_replace(
            [
                'DUMMY_NAMESPACE',
                'DUMMY_REPOSITORY',
                'DUMMY_SERVICE',
                'DUMMY_CLASS_REPOSITORY',
                'DUMMY_CLASS',
            ],
            [
                "App\\$root\\$domain\\Services",
                "App\\$root\\$domain\\Repositories\\$classNameRepository",
                "App\\$root\\Abstracts\AbstractService",
                $classNameRepository,
                ucfirst($domain.'Service'),
            ],
            $content
        );

        $pathFile = app_path($root.'/'.$domain.'/Services'.'/'.ucfirst($domain).'Service.php');

        $this->createFileAndWrite($pathFile, $content);
        $this->info('Service generated successfully.');
    }

    private function controller($domain, $root): void
    {
        $this->warn('Generating controller for '.$domain.' domain.');
        $content = $this->getTemplate('Controller');
        $content = str_replace(
            [
                'DUMMY_NAMESPACE',
                'DUMMY_CLASS',
                'DUMMY_USE_SERVICE',
                'DUMMY_SERVICE',
            ],
            [
                "App\Http\Controllers\\".ucfirst($domain),
                ucfirst($domain).'Controller',
                "App\\$root\\$domain\\Services\\".ucfirst($domain).'Service',
                ucfirst($domain).'Service',
            ],
            $content
        );

        $pathDir = 'Http/Controllers/'.ucfirst($domain);

        if (! file_exists(app_path($pathDir))) {
            mkdir(app_path($pathDir));
        }

        $pathFile = app_path($pathDir.'/'.ucfirst($domain).'Controller.php');

        $this->createFileAndWrite($pathFile, $content);
        $this->info('Controller generated successfully.');
    }

    private function runInfraStructure($domain): void
    {
        $this->warn('Generating infrastructure classes for '.$domain.' domain.');
        $classEntity = ucfirst($domain.'Entity');
        Artisan::call('make:migration create_'.lcfirst($domain).'s_table');
        Artisan::call('make:factory'.' '.$classEntity.'Factory');
        Artisan::call('make:seeder'.' '.$classEntity.'Seeder');
        $this->info('Infrastructure classes generated successfully.');
    }

    private function ensurePackageExists(string $packageName, int $permissions = 0777, bool $recursive = false): void
    {
        if (! is_dir(app_path($packageName))) {
            mkdir(app_path($packageName), $permissions, $recursive);
        }
    }

    private function createFileAndWrite(string $pathFile, string $content): void
    {
        file_put_contents($pathFile, $content);
    }

    private function getTemplate(string $templateName): string
    {
        $templateFiles = glob(base_path("/vendor/**/**/**/Domain/{$templateName}.txt"));

        if (empty($templateFiles)) {
            dd("Template file {$templateName}.txt not found.");
        }

        return file_get_contents($templateFiles[0]);
    }
}
