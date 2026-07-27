<?php

declare(strict_types=1);

namespace Elavora\Api\DataTypes\Email\Tests;

use Elavora\Api\DataTypes\Email;
use PHPUnit\Framework\TestCase;

final class EmailTest extends TestCase
{
    public function testValidatesAndNormalizesEmail(): void
    {
        self::assertSame('team@bifrost.dev', Email::from('TEAM@BIFROST.DEV')->value());
        self::assertFalse(Email::isValid('invalid'));
    }
}
