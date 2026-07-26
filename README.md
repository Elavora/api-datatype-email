# api-datatype-email

[![Packagist Version](https://img.shields.io/packagist/v/elavora/api-datatype-email.svg?style=flat-square)](https://packagist.org/packages/elavora/api-datatype-email)
[![PHP Version](https://img.shields.io/packagist/php-v/elavora/api-datatype-email.svg?style=flat-square)](https://packagist.org/packages/elavora/api-datatype-email)
[![Composer Quality](https://github.com/Elavora/api-datatype-email/actions/workflows/quality.yml/badge.svg?branch=main)](https://github.com/Elavora/api-datatype-email/actions/workflows/quality.yml)
[![CodeQL](https://github.com/Elavora/api-datatype-email/actions/workflows/codeql.yml/badge.svg?branch=main)](https://github.com/Elavora/api-datatype-email/actions/workflows/codeql.yml)
[![License](https://img.shields.io/packagist/l/elavora/api-datatype-email.svg?style=flat-square)](https://packagist.org/packages/elavora/api-datatype-email)

DataType imutavel para validar e normalizar enderecos de e-mail.

## Requisitos

- PHP 8.3 ou superior.
- Demais requisitos declarados em [`composer.json`](composer.json).

## Instalacao

```bash
composer require elavora/api-datatype-email
```

## Inicio rapido

```php
use Elavora\Api\DataTypes\Email;

$valor = Email::from('usuario@example.com');
$normalizado = $valor->value();
```

Espacos externos sao removidos e o endereco e convertido para minusculas sem substituir o dominio.

## Documentacao

Consulte o [guia de uso](docs/USO.md) para detalhes e validacao local.
