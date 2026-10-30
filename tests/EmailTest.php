<?php

declare(strict_types=1);

namespace Elavora\Api\DataTypes\Email\Tests;

use Elavora\Api\DataTypes\AbstractDataType;
use Elavora\Api\DataTypes\Email;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @see docs/USO.md#validação-do-e-mail
 * @see docs/USO.md#criação-e-entrada-inválida
 */
final class EmailTest extends TestCase
{
    private const INVALID_MESSAGE = 'Valor invalido para Elavora\Api\DataTypes\Email.';

    public function testValidatesAndNormalizesEmail(): void
    {
        self::assertSame('team@bifrost.dev', Email::from('TEAM@BIFROST.DEV')->value());
        self::assertFalse(Email::isValid('invalid'));
    }

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function validProvider(): array
    {
        return [
            'minusculo' => ['usuario@example.com', 'usuario@example.com'],
            'caixa mista' => ['Usuario@Example.COM', 'usuario@example.com'],
            'tudo maiusculo' => ['TEAM@BIFROST.DEV', 'team@bifrost.dev'],
            'com tag' => ['usuario+tag@example.com', 'usuario+tag@example.com'],
        ];
    }

    #[DataProvider('validProvider')]
    public function testIsValidAcceptsValue(string $input, string $expected): void
    {
        self::assertTrue(Email::isValid($input));
    }

    #[DataProvider('validProvider')]
    public function testFromNormalizesToLowercase(string $input, string $expected): void
    {
        $email = Email::from($input);

        self::assertSame($expected, $email->value());
        self::assertSame($expected, (string) $email);
        self::assertSame(json_encode($expected), json_encode($email));
    }

    public function testValueIsString(): void
    {
        self::assertIsString(Email::from('Usuario@Example.COM')->value());
    }

    /**
     * @return array<string, array{0: mixed}>
     */
    public static function invalidProvider(): array
    {
        return [
            'sem arroba' => ['invalid'],
            'sem dominio' => ['usuario@'],
            'sem parte local' => ['@example.com'],
            'vazio' => [''],
            'int' => [123],
            'null' => [null],
            'false' => [false],
            'true' => [true],
            'float' => [1.5],
            'array' => [['usuario@example.com']],
            'objeto' => [new \stdClass()],
        ];
    }

    #[DataProvider('invalidProvider')]
    public function testIsValidRejectsValue(mixed $input): void
    {
        self::assertFalse(Email::isValid($input));
    }

    #[DataProvider('invalidProvider')]
    public function testFromThrowsWithDocumentedMessage(mixed $input): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(self::INVALID_MESSAGE);

        Email::from($input);
    }

    public function testIsInstanceOfAbstractDataType(): void
    {
        self::assertInstanceOf(AbstractDataType::class, Email::from('usuario@example.com'));
    }

    public function testConstructorIsNotPublic(): void
    {
        $constructor = (new \ReflectionClass(Email::class))->getConstructor();

        self::assertNotNull($constructor);
        self::assertFalse($constructor->isPublic());
    }

    public function testTestsDoNotInstantiateWithNew(): void
    {
        $needle = 'new ' . 'Email(';

        foreach (glob(__DIR__ . '/*.php') ?: [] as $file) {
            self::assertStringNotContainsString($needle, (string) file_get_contents($file), $file);
        }
    }
}
