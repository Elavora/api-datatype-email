# Guia de uso

`Email` valida o endereco depois de remover espacos externos. O valor armazenado e convertido para minusculas.

```php
use Elavora\Api\DataTypes\Email;

$email = Email::from('  Usuario@Example.COM  ');

echo $email->value(); // usuario@example.com
```

O pacote nao substitui o dominio e nao altera outras regras de internacionalizacao ou semantica do endereco.

Para verificar uma entrada sem criar uma instancia:

```php
if (Email::isValid($entrada)) {
    $email = Email::from($entrada);
}
```

## Validacao do pacote

Execute os comandos a partir da raiz do clone:

```bash
docker run --rm -v "${PWD}:/workspace" -w /workspace composer:2 composer update --no-interaction --no-progress --prefer-dist
docker run --rm -v "${PWD}:/workspace" -w /workspace composer:2 composer check
```
