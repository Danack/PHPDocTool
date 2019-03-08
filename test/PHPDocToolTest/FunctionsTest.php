<?php

declare(strict_types=1);

namespace PHPDocToolTest;

class FunctionsTest extends BaseTestCase
{
    public function providesExtractXmlIdFromManualPathString()
    {
        return [
            ["http://local.docs.phpimagick.com/manual/en/imagick.adaptiveblurimage", "imagick.adaptiveblurimage"],
            ["local.docs.phpimagick.com/manual/en/language.control-structures.php", "language.control-structures"],
        ];
    }

    /**
     * @dataProvider providesExtractXmlIdFromManualPathString
     */
    public function testExtractXmlIdFromManualPathString($input, $expectedOutput)
    {
        $result = extractXmlIdFromManualPathString($input);

        $this->assertSame($expectedOutput, $result);
    }
}

