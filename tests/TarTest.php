<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL v3 or later
 */

namespace Tests\Piwik\Decompress;

use Piwik\Decompress\Tar;

class TarTest extends BaseTest
{
    public function testTarGzFile()
    {
        $filename = $this->fixtureDirectory . '/test.tar.gz';

        $unzip = new Tar($filename, 'gz');
        $res = $unzip->extract($this->tempDirectory);
        $this->assertTrue($res);

        $content = $unzip->listContent();

        $this->assertCount(3, $content);
        $this->assertEquals('tarout1.txt', $content[0]['filename']);
        $this->assertEquals('tardir/tarout2.txt', $content[1]['filename']);
        $this->assertEquals('tardir/', $content[2]['filename']);

        $this->assertFileContentsEquals('TESTDATA', $this->tempDirectory . 'tarout1.txt');
        $this->assertFileContentsEquals('MORETESTDATA', $this->tempDirectory . 'tardir/tarout2.txt');
    }

    public function testUnzipInvalidFile2()
    {
        $filename = $this->fixtureDirectory . '/NotExisting.zip';

        $unzip = new Tar($filename);
        $res = $unzip->extract($this->tempDirectory);
        $this->assertEquals(0, $res);

        $this->assertContains('Unable to open in read mode', $unzip->errorInfo());
    }
}
