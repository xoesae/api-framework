<?php

namespace Core\Stubs;

class MakeCopyStub
{
    protected static function replaceStubContent(string $content, array $replaces): string
    {
        foreach ($replaces as $toReplace => $replaceWith) {
            $content = str_replace($toReplace, $replaceWith, $content);
        }

        return $content;
    }

    protected static function getStubContent(string $stubDir): bool|string
    {
        return file_get_contents($stubDir);
    }

    protected static function getNewDir(string $newDir, string $filename): string
    {
        return $newDir . $filename;
    }
}