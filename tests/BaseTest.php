<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL v3 or later
 */

namespace Tests\Piwik\Decompress;

abstract class BaseTest extends \PHPUnit_Framework_TestCase
{
    protected $fixtureDirectory;
    protected $tempDirectory;

    public function setUp()
    {
        parent::setUp();

        clearstatcache();

        $this->fixtureDirectory = __DIR__ . '/Fixture/';
        $this->tempDirectory = __DIR__ . '/tmp/';
    }

    protected function assertFileContentsEquals($expectedContent, $path)
    {
        $this->assertTrue(file_exists($path));

        $fd = fopen($path, 'rb');
        $actualContent = fread($fd, filesize($path));
        fclose($fd);

        $this->assertEquals($expectedContent, $actualContent);
    }
}
