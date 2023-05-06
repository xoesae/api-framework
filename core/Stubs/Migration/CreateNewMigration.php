<?php

namespace Core\Stubs\Migration;

use Core\Stubs\MakeCopyStub;

class CreateNewMigration extends MakeCopyStub
{
    const stubDir = __DIR__ . '/make_migration.stub';
    const newDir = __DIR__ . '/../../../src/database/migrations/';

    private static function getNameOfNewFile(string $table): string
    {
        $actualDateTime = date('Y_m_d_H_i_s');

        return "{$actualDateTime}_create_{$table}_table.php";
    }

    public function __invoke(string $table): bool
    {
        $newFileName = self::getNameOfNewFile($table);
        $newFileDir = self::getNewDir(self::newDir, $newFileName);

        $content = self::getStubContent(self::stubDir);

        $content = self::replaceStubContent($content, [
            "{{ table }}" => $table
        ]);

        file_put_contents($newFileDir, $content);

        return true;
    }
}


