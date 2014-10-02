<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Tests\Piwik\Decompress;

use Piwik\Decompress\Gzip;
use Piwik\Decompress\PclZip;
use Piwik\Decompress\Tar;
use Piwik\Decompress\ZipArchive;

class DecompressTest extends \PHPUnit_Framework_TestCase
{
    public function testRelativePath()
    {
        clearstatcache();
        $extractDir = __DIR__ . '/tmp/';
        $test = 'relative';
        $filename = dirname(__FILE__) . '/Fixture/' . $test . '.zip';

        if (class_exists('ZipArchive', false)) {
            $unzip = new ZipArchive($filename);
            $res = $unzip->extract($extractDir);
            $this->assertEquals(1, count($res));
            $this->assertFileExists($extractDir . $test . '.txt');
            $this->assertFileNotExists(dirname(__FILE__) . '/' . $test . '.txt');
            $this->assertFileNotExists(dirname(__FILE__) . '/../../tests/' . $test . '.txt');
            unlink($extractDir . $test . '.txt');
        }

        $unzip = new PclZip($filename);
        $res = $unzip->extract($extractDir);
        $this->assertEquals(1, count($res));
        $this->assertFileExists($extractDir . $test . '.txt');
        $this->assertFileNotExists(dirname(__FILE__) . '/' . $test . '.txt');
        $this->assertFileNotExists(dirname(__FILE__) . '/../../tests/' . $test . '.txt');
        unlink($extractDir . $test . '.txt');
    }

    public function testRelativePathAttack()
    {
        clearstatcache();
        $extractDir = __DIR__ . '/tmp/';
        $test = 'zaatt';
        $filename = dirname(__FILE__) . '/Fixture/' . $test . '.zip';

        if (class_exists('ZipArchive', false)) {
            $unzip = new ZipArchive($filename);
            $res = $unzip->extract($extractDir);
            $this->assertEquals(0, $res);
            $this->assertFileNotExists($extractDir . $test . '.txt');
            $this->assertFileNotExists($extractDir . '../' . $test . '.txt');
            $this->assertFileNotExists(dirname(__FILE__) . '/' . $test . '.txt');
            $this->assertFileNotExists(dirname(__FILE__) . '/../' . $test . '.txt');
            $this->assertFileNotExists(dirname(__FILE__) . '/../../' . $test . '.txt');
        }

        $unzip = new PclZip($filename);
        $res = $unzip->extract($extractDir);
        $this->assertEquals(0, $res);
        $this->assertFileNotExists($extractDir . $test . '.txt');
        $this->assertFileNotExists($extractDir . '../' . $test . '.txt');
        $this->assertFileNotExists(dirname(__FILE__) . '/' . $test . '.txt');
        $this->assertFileNotExists(dirname(__FILE__) . '/../' . $test . '.txt');
        $this->assertFileNotExists(dirname(__FILE__) . '/../../' . $test . '.txt');
    }

    public function testAbsolutePathAttack()
    {
        clearstatcache();
        $extractDir = __DIR__ . '/tmp/';
        $test = 'zaabs';
        $filename = dirname(__FILE__) . '/Fixture/' . $test . '.zip';

        if (class_exists('ZipArchive', false)) {
            $unzip = new ZipArchive($filename);
            $res = $unzip->extract($extractDir);
            $this->assertEquals(0, $res);
            $this->assertFileNotExists($extractDir . $test . '.txt');
            $this->assertFileNotExists(dirname(__FILE__) . '/' . $test . '.txt');
        }

        $unzip = new PclZip($filename);
        $res = $unzip->extract($extractDir);
        $this->assertEquals(0, $res);
        $this->assertFileNotExists($extractDir . $test . '.txt');
        $this->assertFileNotExists(dirname(__FILE__) . '/' . $test . '.txt');
    }

    public function testUnzipErrorInfo()
    {
        clearstatcache();
        $filename = dirname(__FILE__) . '/Fixture/zaabs.zip';

        $unzip = new ZipArchive($filename);
        $this->assertContains('No error', $unzip->errorInfo());
    }

    public function testUnzipEmptyFile()
    {
        clearstatcache();
        $filename = dirname(__FILE__) . '/Fixture/empty.zip';
        $extractDir = __DIR__ . '/tmp/';

        $unzip = new ZipArchive($filename);
        $res = $unzip->extract($extractDir);
        $this->assertEquals(0, $res);
    }

    public function testUnzipNotExistingFile()
    {
        clearstatcache();
        $filename = dirname(__FILE__) . '/Fixture/NotExisting.zip';

        try {
            new ZipArchive($filename);
        } catch (\Exception $e) {
            return;
        }
        $this->fail('Exception not raised');
    }

    public function testUnzipInvalidFile2()
    {
        clearstatcache();
        $extractDir = __DIR__ . '/tmp/';
        $filename = dirname(__FILE__) . '/Fixture/NotExisting.zip';

        $unzip = new PclZip($filename);
        $res = $unzip->extract($extractDir);
        $this->assertEquals(0, $res);

        $this->assertContains('PCLZIP_ERR_MISSING_FILE', $unzip->errorInfo());
    }

    public function testGzipFile()
    {
        $extractDir = __DIR__ . '/tmp/';
        $extractFile = $extractDir . 'testgz.txt';
        $filename = dirname(__FILE__) . '/Fixture/test.gz';

        $unzip = new Gzip($filename);
        $res = $unzip->extract($extractFile);
        $this->assertTrue($res);

        $this->assertFileContentsEquals('TESTSTRING', $extractFile);
    }

    public function testTarGzFile()
    {
        $extractDir = __DIR__ . '/tmp/';
        $filename = dirname(__FILE__) . '/Fixture/test.tar.gz';

        $unzip = new Tar($filename, 'gz');
        $res = $unzip->extract($extractDir);
        $this->assertTrue($res);

        $this->assertFileContentsEquals('TESTDATA', $extractDir . 'tarout1.txt');
        $this->assertFileContentsEquals('MORETESTDATA', $extractDir . 'tardir/tarout2.txt');
    }

    private function assertFileContentsEquals($expectedContent, $path)
    {
        $this->assertTrue(file_exists($path));

        $fd = fopen($path, 'rb');
        $actualContent = fread($fd, filesize($path));
        fclose($fd);

        $this->assertEquals($expectedContent, $actualContent);
    }
}
