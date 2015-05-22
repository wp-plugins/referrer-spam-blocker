<?php
namespace Tybulewicz\ReferrerSpamDetector\Tests;

use Tybulewicz\ReferrerSpamDetector\PatternCreator;

class PatternCreatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PatternCreator
     */
    private $creator;

    public function setUp()
    {
        $this->creator = new \Tybulewicz\ReferrerSpamDetector\PatternCreator();
    }

    /** @dataProvider patterns */
    public function testBuildingPattern($list, $expectedPattern)
    {
        $this->assertSame($expectedPattern, $this->creator->generatePattern($list));
    }

    public function patterns()
    {
        return array(
            'one element' => array(array('example.com'), '/(^(.*\/\/)?(.*\.)?example\.com(\/.*)?$)/'),
            'many elements' => array(
                array('example.com', 'example.org'),
                '/(^(.*\/\/)?(.*\.)?example\.com(\/.*)?$)|(^(.*\/\/)?(.*\.)?example\.org(\/.*)?$)/'
            ),
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage can't be empty
     */
    public function testExceptionOnEmptyList()
    {
        $this->creator->generatePattern(array());
    }

    /**
     * @param $referrer
     * @param $shouldMatch
     * @dataProvider getMatches
     */
    public function testMatching($referrer, $shouldMatch)
    {
        $pattern = $this->creator->generatePattern(array('example.com', 'example.org'));

        $matches = preg_match($pattern, $referrer);

        if (false === $matches) {
            $this->fail("Error in preg_match: " . preg_last_error());
        }

        $this->assertSame($shouldMatch, (bool)$matches);
    }

    public function getMatches()
    {
        return array(
            array('', false),
            array('google.com', false),
            array('example.com', true),
            array('example.com/', true),
            array('example.com/dir', true),
            array('example.com/dir/page.html', true),
            array('example.net', false),
            array('anexample.com', false),
            array('http://example.com', true),
            array('example.com/dir', true),
            array('example.com/page.html', true),
            array('domain.example.com', true),
            array('https://domain.example.com/', true),
            array('https://domain.example.com/dir', true),
            array('https://domain.example.com/dir/page.html', true),
        );
    }
}
