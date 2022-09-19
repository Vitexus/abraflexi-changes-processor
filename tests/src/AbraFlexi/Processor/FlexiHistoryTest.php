<?php

namespace Test\AbraFlexi\Processor;

use AbraFlexi\Processor\FlexiHistory;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2022-06-21 at 09:48:25.
 */
class FlexiHistoryTest extends \PHPUnit\Framework\TestCase {

    /**
     * @var FlexiHistory
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void {
        $this->object = new FlexiHistory();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown(): void {
        
    }

    /**
     * @covers AbraFlexi\Processor\FlexiHistory::getPlugins
     * @todo   Implement testgetPlugins().
     */
    public function testgetPlugins() {
        $this->assertEquals('', $this->object->getPlugins());
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    /**
     * @covers AbraFlexi\Processor\FlexiHistory::importHistory
     * @todo   Implement testimportHistory().
     */
    public function testimportHistory() {
        $this->assertEquals('', $this->object->importHistory());
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    /**
     * @covers AbraFlexi\Processor\FlexiHistory::getLastHistoryState
     * @todo   Implement testgetLastHistoryState().
     */
    public function testgetLastHistoryState() {
        $this->assertEquals('', $this->object->getLastHistoryState());
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    /**
     * @covers AbraFlexi\Processor\FlexiHistory::getCurrentData
     * @todo   Implement testgetCurrentData().
     */
    public function testgetCurrentData() {
        $this->assertEquals('', $this->object->getCurrentData());
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    /**
     * @covers AbraFlexi\Processor\FlexiHistory::getPreviousData
     * @todo   Implement testgetPreviousData().
     */
    public function testgetPreviousData() {
        $this->assertEquals('', $this->object->getPreviousData());
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    /**
     * @covers AbraFlexi\Processor\FlexiHistory::getChanges
     * @todo   Implement testgetChanges().
     */
    public function testgetChanges() {
        $this->assertEquals('', $this->object->getChanges());
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

}
