<?php
namespace Tybulewicz\ReferrerSpamDetector\Tests;

use Tybulewicz\ReferrerSpamDetector\PatternCreator;
use Tybulewicz\ReferrerSpamDetector\ReferrerSpamDetector;
use Mockery as M;

class ReferrerSpamDetectorTest extends \PHPUnit_Framework_TestCase
{
    /** @var M\Mock */
    private $patternCreatorMock;

    /** @var  ReferrerSpamDetector */
    private $spamDetector;

    private $defaultList;
    private $defaultPattern;

    public function setUp()
    {
        $this->patternCreatorMock = M::mock('Tybulewicz\ReferrerSpamDetector\PatternCreator');
        $this->spamDetector = new ReferrerSpamDetector($this->patternCreatorMock);

        $this->defaultList = array('example.com', 'example.org');
        $this->defaultPattern = '/(^(.*\/\/)?(.*\.)?example\.com(\/.*)?$)|(^(.*\/\/)?(.*\.)?example\.org(\/.*)?$)/';
    }

    public function tearDown()
    {
        M::close();
    }

    /**
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage No spam list added
     */
    public function testExceptionOnEmptyList()
    {
        $this->spamDetector->isSpam('example.com');
    }

    /**
     * @param $referrer
     * @param $isSpam
     * @dataProvider detectionResults
     */
    public function testDetection($referrer, $isSpam)
    {
        $this->setUpPatternCreatorMock();

        $this->spamDetector->setSpamList($this->defaultList);
        $this->assertSame($isSpam, $this->spamDetector->isSpam(($referrer)));
    }

    private function setUpPatternCreatorMock()
    {
        $this->patternCreatorMock->shouldReceive('generatePattern')->with($this->defaultList)->once()->andReturn($this->defaultPattern);
    }

    public function detectionResults()
    {
        return array(
            array('', false),
            array('google.com', false),
            array('example.com', true),
            array('https://example.com/dir/page.html', true),
            array('example.net', false),
        );
    }
}
