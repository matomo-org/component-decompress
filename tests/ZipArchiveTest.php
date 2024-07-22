<?php
/**
 * Matomo - free/libre analytics platform
 *
 * @link https://matomo.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL v3 or later
 */

namespace Tests\Matomo\Decompress;

use Matomo\Decompress\ZipArchive;

class ZipArchiveTest extends TestBase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (! class_exists(\ZipArchive::class)) {
            $this->markTestSkipped('The PHP zip extension is not installed, skipping ZipArchive tests');
        }
    }

    public function testRelativePath()
    {
        $test = 'relative';
        $filename = $this->fixtureDirectory . $test . '.zip';

        $unzip = new ZipArchive($filename);
        $res = $unzip->extract($this->tempDirectory);
        $this->assertCount(1, $res);
        $this->assertFileExists($this->tempDirectory . $test . '.txt');
        $this->assertFileDoesNotExist(__DIR__ . '/' . $test . '.txt');
        $this->assertFileDoesNotExist(__DIR__ . '/../../tests/' . $test . '.txt');
        unlink($this->tempDirectory . $test . '.txt');
    }

    public function testRelativePathAttack()
    {
        $test = 'zaatt';
        $filename = $this->fixtureDirectory . $test . '.zip';

        $unzip = new ZipArchive($filename);
        $res = $unzip->extract($this->tempDirectory);
        $this->assertEquals(0, $res);
        $this->assertFileDoesNotExist($this->tempDirectory . $test . '.txt');
        $this->assertFileDoesNotExist($this->tempDirectory . '../' . $test . '.txt');
        $this->assertFileDoesNotExist(__DIR__ . '/' . $test . '.txt');
        $this->assertFileDoesNotExist(__DIR__ . '/../' . $test . '.txt');
        $this->assertFileDoesNotExist(__DIR__ . '/../../' . $test . '.txt');
    }

    public function testAbsolutePathAttack()
    {
        $test = 'zaabs';
        $filename = $this->fixtureDirectory . $test . '.zip';

        $unzip = new ZipArchive($filename);
        $res = $unzip->extract($this->tempDirectory);
        $this->assertEquals(0, $res);
        $this->assertFileDoesNotExist($this->tempDirectory . $test . '.txt');
        $this->assertFileDoesNotExist(__DIR__ . '/' . $test . '.txt');
    }

    public function testUnzipErrorInfo()
    {
        $filename = $this->fixtureDirectory . '/zaabs.zip';

        $unzip = new ZipArchive($filename);
        $this->assertStringContainsString('No error', $unzip->errorInfo());
    }

    public function testUnzipEmptyFile()
    {
        $filename = $this->fixtureDirectory . '/empty.zip';

        $unzip = new ZipArchive($filename);
        $res = $unzip->extract($this->tempDirectory);
        $this->assertEquals(0, $res);
    }

    public function testExtractOnNoSlashPathExtracted()
    {
        $filename = $this->fixtureDirectory . '/empty.zip';
        $tempDirectory = '/tmp';

        $unzip = new ZipArchive($filename);
        $res = $unzip->extract($tempDirectory);
        $this->assertEquals(0, $res);
    }

    public function testUnzipNotExistingFile()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessageMatches('/^Error opening/');

        new ZipArchive($this->fixtureDirectory . '/NotExisting.zip');
    }
}
