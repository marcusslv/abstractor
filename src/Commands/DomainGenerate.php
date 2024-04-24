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
        $root = $this->ask('Informe o nome do pacote');

        $domain = $this->ask('Informe o dominio');

        mkdir(app_path($root.'/'.ucfirst($domain)), 0755, true);
        mkdir(app_path($root.'/'.ucfirst($domain).'/Entities'));
        mkdir(app_path($root.'/'.ucfirst($domain).'/Repositories'));
        mkdir(app_path($root.'/'.ucfirst($domain).'/Services'));
        mkdir(app_path($root.'/'.ucfirst($domain).'/ValueObjects'));

        $this->entity($domain, $root);
        $this->repository($domain, $root);
        $this->service($domain, $root);

        $this->runInfraStructure($domain);
    }

    private function entity($domain, $root): void
    {
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
    }

    private function repository($domain, $root): void
    {
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
    }

    private function getTemplate(string $templateName): string
    {
        $templateFiles = glob(base_path("/vendor/**/**/**/**/{$templateName}.txt"));

        if (empty($templateFiles)) {
            dd("Template file {$templateName}.txt not found.");
        }

        return file_get_contents($templateFiles[0]);
    }

    private function service($domain, $root): void
    {
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
    }

    private function createFileAndWrite(string $pathFile, string $content): void
    {
        file_put_contents($pathFile, $content);
    }

    private function runInfraStructure($domain): void
    {
        $classEntity = ucfirst($domain.'Entity');
        Artisan::call('make:migration create_'.lcfirst($domain).'s_table');
        Artisan::call('make:factory'.' '.$classEntity.'Factory');
        Artisan::call('make:seeder'.' '.$classEntity.'Seeder');
        Artisan::call('make:controller'.' '.ucfirst($domain).'/'.ucfirst($domain).'Controller');
    }
}
