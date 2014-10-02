<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Tests\Piwik\Decompress;

use Piwik\Decompress\Gzip;

class GzipTest extends BaseTest
{
    public function testGzipFile()
    {
        $extractFile = $this->tempDirectory . 'testgz.txt';
        $filename = $this->fixtureDirectory . '/test.gz';

        $unzip = new Gzip($filename);
        $res = $unzip->extract($extractFile);
        $this->assertTrue($res);

        $this->assertFileContentsEquals('TESTSTRING', $extractFile);
    }
}
