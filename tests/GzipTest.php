<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL v3 or later
 */

namespace Tests\Piwik\Decompress;

use Piwik\Decompress\Gzip;

class GzipTest extends BaseTest
{
    public function testGzipFile()
    {
        $extractFile = $this->tempDirectory . 'testgz.txt';

        $unzip = new Gzip($this->fixtureDirectory . '/test.gz');
        $res = $unzip->extract($extractFile);
        $this->assertTrue($res);

        $this->assertFileContentsEquals('TESTSTRING', $extractFile);
    }
}
