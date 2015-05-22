<?php
namespace Tybulewicz\ReferrerSpamDetector;

class Detector {
    /**
     * @var ReferrerSpamDetector
     */
    private $spamDetector;

    public function __construct() {
        $domains = file(__DIR__ . '/../../../piwik/referrer-spam-blacklist/spammers.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        $this->spamDetector = new ReferrerSpamDetector(new PatternCreator());
        $this->spamDetector->setSpamList($domains);
    }

    /**
     * Check if given referrer string is matched by configured rules
     * @param $referrer
     * @return bool
     */
    public function isSpam($referrer) {
        return $this->spamDetector->isSpam($referrer);
    }
}
