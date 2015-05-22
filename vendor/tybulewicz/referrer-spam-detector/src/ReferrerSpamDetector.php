<?php
namespace Tybulewicz\ReferrerSpamDetector;

class ReferrerSpamDetector {

    /**
     * @var PatternCreator
     */
    private $patternCreator;

    /**
     * @var string
     */
    private $pattern;

    public function __construct(PatternCreator $patternCreator) {
        $this->patternCreator = $patternCreator;
    }

    /**
     * Setup rules for given list of known spam domains
     * @param array $list
     * @return self
     */
    public function setSpamList(array $list) {
        $this->pattern = $this->patternCreator->generatePattern($list);
        return $this;
    }

    /**
     * Check if given referrer string is matched by configured rules
     * @param $referrer
     * @return bool
     */
    public function isSpam($referrer) {
        if (empty($this->pattern)) {
            throw new \BadMethodCallException("No spam list added");
        }

        $result = preg_match($this->pattern, $referrer);
        if (false === $result) {
            throw new \BadMethodCallException();
        }

        return (bool) $result;
    }
}
