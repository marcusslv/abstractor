# CodeHubMVS

## Project Name: CodeHubMVS Abstracts

CodeHubMVS Abstracts is a PHP library that provides a set of abstract classes to assist in the development of Laravel applications. 
It includes the commands `make:abstracts` and `make:domain`, which generate domain classes such as Entity, Repository, Service, and Controller. 
Additionally, it also generates infrastructure classes like migrations, factories, and seeders.

## Installation

To install the Braip Abstracts package, you need to have Composer installed on your system. If you don't have Composer installed, you can download it from [here](https://getcomposer.org/).

Once you have Composer installed, you can add Braip Abstracts to your project by running the following command in your project's root directory:

```bash
composer require braip/pkg-abstracts --dev
```
## Usage

After installing the package, you can use the `make:abstract` command to generate abstracts classes. 
Here is an example of how to use it:

```bash
php artisan make:astract
```

This command will generate the following abstract classes:

- `Entity`
- `Repository`
- `Service`
- `Controller`
- `Interface`

```bash
php artisan make:domain
```

This command will generate the following domain classes:

- `Entity`
- `Repository`
- `Service`
- `Controller`
- `Interface`
- `Migration`
- `Factory`
- `Seeder`

