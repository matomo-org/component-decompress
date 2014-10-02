<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Tests\Piwik\Decompress;

use Piwik\Decompress\ZipArchive;

class ZipArchiveTest extends BaseTest
{
    public function testRelativePath()
    {
        $test = 'relative';
        $filename = $this->fixtureDirectory . $test . '.zip';

        if (class_exists('ZipArchive', false)) {
            $unzip = new ZipArchive($filename);
            $res = $unzip->extract($this->tempDirectory);
            $this->assertEquals(1, count($res));
            $this->assertFileExists($this->tempDirectory . $test . '.txt');
            $this->assertFileNotExists(__DIR__ . '/' . $test . '.txt');
            $this->assertFileNotExists(__DIR__ . '/../../tests/' . $test . '.txt');
            unlink($this->tempDirectory . $test . '.txt');
        }
    }

    public function testRelativePathAttack()
    {
        $test = 'zaatt';
        $filename = $this->fixtureDirectory . $test . '.zip';

        if (class_exists('ZipArchive', false)) {
            $unzip = new ZipArchive($filename);
            $res = $unzip->extract($this->tempDirectory);
            $this->assertEquals(0, $res);
            $this->assertFileNotExists($this->tempDirectory . $test . '.txt');
            $this->assertFileNotExists($this->tempDirectory . '../' . $test . '.txt');
            $this->assertFileNotExists(__DIR__ . '/' . $test . '.txt');
            $this->assertFileNotExists(__DIR__ . '/../' . $test . '.txt');
            $this->assertFileNotExists(__DIR__ . '/../../' . $test . '.txt');
        }
    }

    public function testAbsolutePathAttack()
    {
        $test = 'zaabs';
        $filename = $this->fixtureDirectory . $test . '.zip';

        if (class_exists('ZipArchive', false)) {
            $unzip = new ZipArchive($filename);
            $res = $unzip->extract($this->tempDirectory);
            $this->assertEquals(0, $res);
            $this->assertFileNotExists($this->tempDirectory . $test . '.txt');
            $this->assertFileNotExists(__DIR__ . '/' . $test . '.txt');
        }
    }

    public function testUnzipErrorInfo()
    {
        $filename = $this->fixtureDirectory . '/zaabs.zip';

        $unzip = new ZipArchive($filename);
        $this->assertContains('No error', $unzip->errorInfo());
    }

    public function testUnzipEmptyFile()
    {
        $filename = $this->fixtureDirectory . '/empty.zip';

        $unzip = new ZipArchive($filename);
        $res = $unzip->extract($this->tempDirectory);
        $this->assertEquals(0, $res);
    }

    public function testUnzipNotExistingFile()
    {
        $filename = $this->fixtureDirectory . '/NotExisting.zip';

        try {
            new ZipArchive($filename);
        } catch (\Exception $e) {
            return;
        }
        $this->fail('Exception not raised');
    }
}
