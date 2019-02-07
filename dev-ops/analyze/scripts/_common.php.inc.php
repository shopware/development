<?php

require_once __DIR__ . '/../vendor/autoload.php';

class AnalyzeCommons
{

    private static $processes = [];

    public static function putImageConfig(string $fileName, string $name, string $path)
    {
        self::putArtifact("$fileName-analyze-config.json",
            json_encode([
                'name' => $name,
                'path' => $path
            ])
        );
    }

    public static function putArtifact(string $fileName, string $contents)
    {
        file_put_contents(
            __DIR__ . "/../../../build/artifacts/$fileName",
            $contents
        );
    }

    public static function normalizePath(string $realPath) {
        return substr($realPath, strlen(dirname(__DIR__, 3) . '/vendor/shopware/platform'));
    }

    public static function normalizeFileInfo(\Symfony\Component\Finder\SplFileInfo $fileInfo): string {
        return self::normalizePath($fileInfo->getRealPath());
    }

    public static function start(string $name, \Symfony\Component\Process\Process $process)
    {
        $process->start(static function (string $type, string $data): void {
//            echo $data;
        });

        self::$processes[$name] = $process;
    }

    public static function wait() {
        /** @var \Symfony\Component\Process\Process $process */
        foreach (self::$processes as $file => $process) {
            while($process->isRunning()) {
                sleep(1);
                echo "WAITING\n";
            }

            echo "$file Done!\n";
        }

        echo "ALL DONE\n";
    }
}
