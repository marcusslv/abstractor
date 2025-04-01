# 📦 Pacote de Abstração de Domínio - Laradom

## Visão Geral 🧱📚

**Laradom** é uma biblioteca PHP criada para auxiliar o desenvolvimento de aplicações Laravel por meio da geração de classes abstratas reutilizáveis. O pacote oferece os comandos `make:abstracts` e `make:domain`, que automatizam a criação de estruturas essenciais do domínio como **Entity**, **Repository**, **Service** e **Controller**. Também são geradas classes de infraestrutura como **migrations**, **factories** e **seeders**.

## 🧩 Instalação

Para instalar o pacote **Laradom**, é necessário ter o **Composer** instalado em seu sistema. Caso não possua, você pode baixá-lo em [getcomposer.org](https://getcomposer.org).

Com o Composer instalado, execute o seguinte comando no diretório raiz do seu projeto Laravel:

```bash
composer require codehubmvs/pkg-abstracts --dev
```

## 🚀 Como Utilizar

Após a instalação do pacote, os seguintes comandos estão disponíveis via Artisan:

### 🔧 Geração de Classes Abstratas

```bash
php artisan make:abstract
```

Este comando gerará as seguintes classes abstratas:

- Entity
- Repository
- Service
- Controller
- Interface

### 🏗️ Geração de Classes de Domínio

```bash
php artisan make:domain
```

Este comando gerará as seguintes classes:

- Entity
- Repository
- Service
- Controller
- Interface
- Migration
- Factory
- Seeder

## 🎯 Objetivo

Este pacote foi desenvolvido com foco em **padronização de código**, **reuso** e **agilidade** no desenvolvimento de aplicações Laravel com base em uma arquitetura bem definida e orientada a domínio.

## 🤝 Contribuições

Contribuições são bem-vindas! Fique à vontade para abrir issues ou enviar pull requests.

## 📄 Licença

Este projeto está licenciado sob a Licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais informações.

