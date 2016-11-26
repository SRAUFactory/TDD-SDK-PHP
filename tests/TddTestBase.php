<?php
namespace TddTest;
use \PHPUnit_Framework_TestCase;
/**
 * The test base class on TDD.
 * To inherit this class when TDD test class implement.
 */ 
class TddTestBase extends PHPUnit_Framework_TestCase {
    /**
     * The instance object to test class
     */
    protected $target;

    /**
     * Directories for system
     */ 
    private static $SYSTEM_DIR = ["src", "tests", "templates"];

    /**
     * @override
     * @see
     */
    public function setUp() {
        parent::setUp();

        $dir = getenv(TEST_OUTPUT_DIR);
        if (!file_exists($dir)) {
            mkdir($dir);
        }
    }
 
    /**
     * @override
     * @see
     */
    public function tearDown() {
        unset($this->target);
        parent::tearDown();

        $dir = getenv(TEST_OUTPUT_DIR);
        if (file_exists($dir) && !in_array($dir, self::$SYSTEM_DIR)) {
            $files = scandir($dir);
            array_walk($files, [$this, "removeFile"], $dir);
            rmdir($dir);
        }
    }

    /**
     * Remove File
     * @param string $file File Name
     * @param int $key The index of File List
     * @param string $dir Target Directory
     */ 
    private function removeFile($file, $key, $dir) {
        unlink("{$dir}/{$file}");
    } 
}
