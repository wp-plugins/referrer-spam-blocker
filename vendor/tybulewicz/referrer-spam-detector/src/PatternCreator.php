<?php
namespace Tybulewicz\ReferrerSpamDetector;


class PatternCreator
{
    public function generatePattern(array $list)
    {
        if (empty($list)) {
            throw new \InvalidArgumentException("List of known spammers can't be empty");
        }

        $pattern_start = '(^(.*\/\/)?(.*\.)?';
        $pattern_end = '(\/.*)?$)';

        $escapedList = array_map('preg_quote', $list);
        return "/{$pattern_start}" . implode("{$pattern_end}|{$pattern_start}",
            $escapedList) . "{$pattern_end}/";
    }
}
