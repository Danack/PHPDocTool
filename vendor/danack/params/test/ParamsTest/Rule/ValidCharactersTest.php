<?php

declare(strict_types=1);

namespace ParamsTest\Rule;

use ParamsTest\BaseTestCase;
use Params\Rule\ValidCharacters;

class ValidCharactersTest extends BaseTestCase
{
    public function provideTestCases()
    {
        return [
            ['a-zA-Z', 'john', null],
            ['a-zA-Z', 'johnny-5', 6],  // bad digit and hyphen
            ['a-zA-Z', 'jo  hn', 2], // bad space
        ];
    }

    /**
     * @dataProvider provideTestCases
     * @covers \Params\Rule\ValidCharacters
     */
    public function testValidation($validCharactersPattern, $testValue, $expectedErrorPosition)
    {
        $validator = new ValidCharacters($validCharactersPattern);
        $validationResult = $validator('foo', $testValue);
        if ($expectedErrorPosition !== null) {
            $this->assertNotNull($validationResult->getProblemMessage());
            $this->assertContains((string)$expectedErrorPosition, $validationResult->getProblemMessage());
            $this->assertContains($validCharactersPattern, $validationResult->getProblemMessage());
        }
        else {
            $this->assertNull($validationResult->getProblemMessage());
        }
    }
}
