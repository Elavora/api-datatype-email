<?php

declare(strict_types=1);

namespace Elavora\Api\DataTypes\Email\Tests;

use Elavora\Api\DataTypes\Email;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class EmailInputTest extends TestCase
{
    public function testValidatesAndNormalizesTrimmedEmail(): void
    {
        self::assertSame(
            'usuario@example.com',
            Email::from('  Usuario@Example.COM  ')->value()
        );
    }

    public function testPreservesDomainWhileNormalizingCase(): void
    {
        self::assertSame(
            'team@bifrost.dev',
            Email::from('TEAM@BIFROST.DEV')->value()
        );
    }

    #[DataProvider('invalidValues')]
    public function testRejectsInvalidAndUnsupportedValues(mixed $value): void
    {
        self::assertFalse(Email::isValid($value));
    }

    /**
     * @return iterable<string, array{mixed}>
     */
    public static function invalidValues(): iterable
    {
        yield 'empty string' => [''];
        yield 'only spaces' => ['   '];
        yield 'invalid format' => ['usuario@'];
        yield 'integer' => [123];
        yield 'array' => [['usuario@example.com']];
        yield 'object' => [new class {}];
        yield 'null' => [null];
    }
}
