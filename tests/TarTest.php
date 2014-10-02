<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
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

        $this->assertFileContentsEquals('TESTDATA', $this->tempDirectory . 'tarout1.txt');
        $this->assertFileContentsEquals('MORETESTDATA', $this->tempDirectory . 'tardir/tarout2.txt');
    }
}
