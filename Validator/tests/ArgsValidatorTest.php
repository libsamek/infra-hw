<?php

namespace Samek\Validator;

use PHPUnit\Framework\TestCase;
use Respect\Validation\Exceptions\NestedValidationException;
use Samek\Validator\Validators\ArgsValidator;

class ArgsValidatorTest extends TestCase
{
    public function testValidArgsValidator()
    {
        $validTestArgs = [
          "USER_ID" => "85010c53-eafd-4ce2-93c0-5818b593c25a"
        ];

        $argsValidator = new ArgsValidator($validTestArgs);
        $this->assertTrue($argsValidator->validate());
    }

    public function testInvalidArgsValidator()
    {
        $this->expectException(NestedValidationException::class);

        $invalidTestArgs = [
            "USER_ID" => "zaba"
        ];

        $argsValidator = new ArgsValidator($invalidTestArgs);
        $argsValidator->validate();
    }

    public function testIncompleteArgsValidator()
    {
        $this->expectException(NestedValidationException::class);

        $incompleteTestArgs = [
            "id" => 5
        ];

        $argsValidator = new ArgsValidator($incompleteTestArgs);
        $argsValidator->validate();
    }
}
