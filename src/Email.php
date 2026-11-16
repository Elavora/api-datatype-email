<?php

declare(strict_types=1);

namespace Elavora\Api\DataTypes;

use Elavora\Api\Framework\Contracts\DescribesType;

final readonly class Email extends AbstractDataType implements DescribesType
{
    /**
     * Descreve o tipo no OPTIONS publico da API.
     *
     * @return array<string, mixed>
     */
    public static function documentedType(): array
    {
        return [
            'type' => 'string',
            'format' => 'email',
            'example' => 'nome@exemplo.com',
            'description' => 'Endereco de e-mail valido. O valor retornado vem em minusculas e sem espacos nas pontas.',
        ];
    }

    /**
     * Verifica se o valor e um e-mail valido.
     */
    public static function isValid(mixed $value): bool
    {
        return is_string($value) && filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    protected static function normalize(mixed $value): string
    {
        return strtolower(trim((string) $value));
    }
}
