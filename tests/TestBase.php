<?php
/**
 * Matomo - free/libre analytics platform
 *
 * @link https://matomo.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL v3 or later
 */

namespace Tests\Matomo\Decompress;

use PHPUnit\Framework\TestCase;

abstract class TestBase extends TestCase
{
    protected $fixtureDirectory;
    protected $tempDirectory;

    protected function setUp(): void
    {
        parent::setUp();

        clearstatcache();

        $this->fixtureDirectory = __DIR__ . '/Fixture/';
        $this->tempDirectory = __DIR__ . '/tmp/';
    }

    protected function assertFileContentsEquals($expectedContent, $path)
    {
        $this->assertFileExists($path);

        $fd = fopen($path, 'rb');
        $actualContent = fread($fd, filesize($path));
        fclose($fd);

        $this->assertEquals($expectedContent, $actualContent);
    }

    /**
     * Asserts that a file does not exist.
     *
     * This function is for PHPUnit 8 compatibility
     * Remove it when PHPUnit 8 is removed and replace with assertFileDoesNotExist
     */
    public static function assertFileNotExists(string $filename, string $message = ''): void
    {
        if (!method_exists(parent::class, 'assertFileDoesNotExist')) {
            parent::assertFileNotExists($filename, $message);
            return;
        }

        parent::assertFileDoesNotExist($filename, $message);
    }
}
