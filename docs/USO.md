# Guia de uso

DataType de e-mail para aplicações Elavora API.

## Instalação

```bash
composer require elavora/api-datatype-email
```

## Quando usar

- Validar e normalizar valores antes de chegar na regra de negócio.
- Evitar passar strings soltas entre services, DTOs e persistência.
- Reutilizar a mesma validação em controllers, comandos e testes.

## Exemplo rápido

```php
use Elavora\Api\DataTypes\Email;

$email = Email::from('Usuario@Example.COM');

$email->value();                          // 'usuario@example.com'
(string) $email;                          // 'usuario@example.com'
json_encode($email);                      // '"usuario@example.com"'
Email::isValid('usuario@example.com');    // true
```

O construtor não é público: crie sempre por `Email::from()`.

## Versão e compatibilidade

| Item | Valor |
|---|---|
| Última versão antes do upgrade | `0.2.0` (tag legada `v0.2.0`) |
| Versão do upgrade | `1.0.0` |
| PHP | `>=8.3` |
| `elavora/api-datatype-core` | `^2.0` |

As versões seguintes saem do fluxo central de release: veja [Versões e release](AUTOMACAO.md#versões-e-release).
A versão `1.0.0` atualiza o pacote para o `elavora/api-datatype-core` `^2.0` (que traz o framework 2) e exige PHP 8.3
ou superior.

**Quebra de compatibilidade:** no `elavora/api-datatype-core` 2 o construtor de `AbstractDataType` é
`final protected`, então `new Email('usuario@example.com')` deixa de funcionar fora da própria classe. Troque por
`Email::from('usuario@example.com')`. `Email::isValid()` e `value()` continuam iguais.

### `composer.json`

O `composer.json` do pacote deve ter:

- `require.php` = `">=8.3"` e `require.elavora/api-datatype-core` = `"^2.0"`
- `extra.branch-alias.dev-main` = `"1.0-dev"`
- `autoload.psr-4` = `"Elavora\Api\DataTypes\\": "src/"` (sem mudança)
- `require-dev.phpunit/phpunit` = `"^10.5 || ^11.0 || ^12.0"` (sem mudança)
- `scripts.test` = `"phpunit -c phpunit.xml"` (sem mudança)
- `require-dev`, `scripts` e `config` de qualidade (`elavora/code-standards`, PHPStan, `lint`, `analyse`, `check`):
  veja [`composer.json` em Automação](AUTOMACAO.md#composerjson)
- nenhum `repositories` (nem `path`, nem `vcs`)
- `composer validate --strict --no-check-publish` sem erros.

## Criação e entrada inválida

`Elavora\Api\DataTypes\Email::from(mixed $value): static` valida o valor com `isValid()` e devolve um `Email` com o
valor normalizado (em minúsculas). Se o valor for inválido, lança `\InvalidArgumentException` com a mensagem
`Valor invalido para Elavora\Api\DataTypes\Email.`

```php
use Elavora\Api\DataTypes\Email;

try {
    Email::from('invalid');
} catch (\InvalidArgumentException $e) {
    echo $e->getMessage(); // Valor invalido para Elavora\Api\DataTypes\Email.
}
```

Métodos da instância: `value(): string|int|bool|float|null` (aqui sempre `string`), `jsonSerialize()` (mesmo valor) e
`__toString()`.

## Validação do e-mail

`Elavora\Api\DataTypes\Email::isValid(mixed $value): bool` não lança exceção. O valor só é válido quando é uma
`string` aceita pelo filtro `FILTER_VALIDATE_EMAIL` do PHP. Qualquer outro tipo (`int`, `bool`, `null`, `array`,
objeto) é inválido. A normalização converte o valor para minúsculas.

| Entrada | `isValid` | `from()` / valor normalizado |
|---|---|---|
| `'usuario@example.com'` | `true` | `usuario@example.com` |
| `'Usuario@Example.COM'` | `true` | `usuario@example.com` |
| `'usuario+tag@example.com'` | `true` | `usuario+tag@example.com` |
| `'invalid'` (sem `@`) | `false` | lança `InvalidArgumentException` |
| `'usuario@'` (sem domínio) | `false` | lança `InvalidArgumentException` |
| `'@example.com'` (sem parte local) | `false` | lança `InvalidArgumentException` |
| `''` | `false` | lança `InvalidArgumentException` |
| `123` (`int`) | `false` | lança `InvalidArgumentException` |
| `null`, `false` | `false` | lança `InvalidArgumentException` |

Detalhes que a tabela mostra:

- A validação vem do filtro do PHP, e a normalização só muda a caixa das letras. Quem decide o que é um e-mail
  aceito é o `FILTER_VALIDATE_EMAIL`; este pacote não acrescenta regras de domínio nem consulta DNS.
- O valor normalizado é sempre `string`.

Contraexemplo (comportamento que não vale mais): `new Email('usuario@example.com')` funcionava no
`elavora/api-datatype-core` `^0.1` e falha no `^2.0`, porque o construtor é `final protected`.

## Principais pontos de entrada

- `Elavora\Api\DataTypes\Email` (`final readonly`, herda de `Elavora\Api\DataTypes\AbstractDataType`)

## Dependências de runtime

- `elavora/api-datatype-core` `^2.0`

## Validação do pacote

O passo a passo para rodar lint, PHPStan e testes em container está em [Desenvolvimento e testes](DESENVOLVIMENTO.md).
Na aplicação consumidora, rode os testes dela depois de instalar o pacote.

## Observações

- Mantenha regras de produto fora deste pacote.
- Prefira configurar extensões no bootstrap da aplicação.
- Instale apenas os módulos que a aplicação realmente usa.
