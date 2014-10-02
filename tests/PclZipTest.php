<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL v3 or later
 */

namespace Tests\Piwik\Decompress;

use Piwik\Decompress\PclZip;

class PclZipTest extends BaseTest
{
    public function testRelativePath()
    {
        $test = 'relative';
        $filename = $this->fixtureDirectory . $test . '.zip';

        $unzip = new PclZip($filename);
        $res = $unzip->extract($this->tempDirectory);
        $this->assertEquals(1, count($res));
        $this->assertFileExists($this->tempDirectory . $test . '.txt');
        $this->assertFileNotExists(__DIR__ . '/' . $test . '.txt');
        $this->assertFileNotExists(__DIR__ . '/../../tests/' . $test . '.txt');
        unlink($this->tempDirectory . $test . '.txt');
    }

    public function testRelativePathAttack()
    {
        $test = 'zaatt';
        $filename = $this->fixtureDirectory . $test . '.zip';

        $unzip = new PclZip($filename);
        $res = $unzip->extract($this->tempDirectory);
        $this->assertEquals(0, $res);
        $this->assertFileNotExists($this->tempDirectory . $test . '.txt');
        $this->assertFileNotExists($this->tempDirectory . '../' . $test . '.txt');
        $this->assertFileNotExists(__DIR__ . '/' . $test . '.txt');
        $this->assertFileNotExists(__DIR__ . '/../' . $test . '.txt');
        $this->assertFileNotExists(__DIR__ . '/../../' . $test . '.txt');
    }

    public function testAbsolutePathAttack()
    {
        $test = 'zaabs';
        $filename = $this->fixtureDirectory . $test . '.zip';

        $unzip = new PclZip($filename);
        $res = $unzip->extract($this->tempDirectory);
        $this->assertEquals(0, $res);
        $this->assertFileNotExists($this->tempDirectory . $test . '.txt');
        $this->assertFileNotExists(__DIR__ . '/' . $test . '.txt');
    }

    public function testUnzipInvalidFile2()
    {
        $filename = $this->fixtureDirectory . '/NotExisting.zip';

        $unzip = new PclZip($filename);
        $res = $unzip->extract($this->tempDirectory);
        $this->assertEquals(0, $res);

        $this->assertContains('PCLZIP_ERR_MISSING_FILE', $unzip->errorInfo());
    }
}
