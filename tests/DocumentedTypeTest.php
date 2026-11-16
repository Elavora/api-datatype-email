<?php

declare(strict_types=1);

namespace Elavora\Api\DataTypes\Email\Tests;

use Elavora\Api\DataTypes\Email;
use Elavora\Api\Framework\Contracts\DescribesType;
use PHPUnit\Framework\TestCase;

/**
 * @see docs/USO.md#documentação-do-tipo-options
 */
final class DocumentedTypeTest extends TestCase
{
    private const ALLOWED_KEYS = ['type', 'format', 'mask', 'pattern', 'enum', 'example', 'description'];

    public function testImplementsDescribesType(): void
    {
        self::assertInstanceOf(DescribesType::class, new Email('nome@exemplo.com'));
    }

    public function testDocumentedTypeUsesOnlyAllowedKeys(): void
    {
        $documented = Email::documentedType();

        self::assertArrayHasKey('type', $documented);
        self::assertSame([], array_diff(array_keys($documented), self::ALLOWED_KEYS));
    }

    public function testExampleIsValidAndMatchesPattern(): void
    {
        $documented = Email::documentedType();

        if (isset($documented['example'])) {
            self::assertTrue(Email::isValid($documented['example']));
        }

        if (isset($documented['pattern'], $documented['example'])) {
            self::assertSame(1, preg_match('~' . $documented['pattern'] . '~', (string) $documented['example']));
        }
    }
}
