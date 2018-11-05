<?php

namespace Samek\Validator;

use PHPUnit\Framework\TestCase;
use Respect\Validation\Validator as v;

class UuidTest extends TestCase
{
    public static function setUpBeforeClass()
    {
        v::with("Samek\\Validator\\Validators\\");
    }

    public function testUuid()
    {
        $uuidValidator = v::uuid();

        $this->assertTrue($uuidValidator->validate("85010c53-eafd-4ce2-93c0-5818b593c25a"));

        $this->assertFalse($uuidValidator->validate("85010c53-eafd-1ce2-93c0-5818b593c25a"));
        $this->assertFalse($uuidValidator->validate("85010c53-eafd-4ce2-33c0-5818b593c25a"));
        $this->assertFalse($uuidValidator->validate("85010c53-eafd-4ce2-93c0-5818b593c25z"));

        $this->assertFalse($uuidValidator->validate("žaba"));
    }
}
