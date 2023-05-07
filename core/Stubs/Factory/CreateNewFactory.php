<?php

namespace Core\Stubs\Factory;

use Core\Stubs\MakeCopyStub;

class CreateNewFactory extends MakeCopyStub
{
    const STUB_DIR = __DIR__ . '/make_factory.stub';
    const NEW_DIR = __DIR__ . '/../../../src/database/factories/';

    private static function getNameOfNewFile(string $modelName): string
    {
        return "{$modelName}Factory.php";
    }

    public function __invoke(string $modelName): bool
    {
        $newFileName = self::getNameOfNewFile($modelName);
        $newFileDir = self::getNewDir(self::NEW_DIR, $newFileName);

        $content = self::getStubContent(self::STUB_DIR);

        $content = self::replaceStubContent($content, [
            "{{ class }}" => "{$modelName}Factory",
            "{{ model }}" => $modelName,
        ]);

        file_put_contents($newFileDir, $content);

        return true;
    }
}