<?php

namespace App\Traits;

trait AbbreviatesStrings
{
    public function abbreviate($string)
    {
        // Split the string into words
        $words = explode(' ', $string);

        $abbreviation = '';

        // Handle special cases after the first word
        if (count($words) > 1) {
            // Special case: If there's a dash or hyphen after the first word, take one letter
            if (strpos($words[1], '-') !== false || strpos($words[1], '-') !== false) {
                $abbreviation = strtoupper($words[0][0]);
            } else {
                // Otherwise, take the first letter of the first two words
                $abbreviation = strtoupper($words[0][0] . $words[1][0]);
            }
        } else {
            // If there's only one word, take the first letter
            $abbreviation = strtoupper($words[0][0]);
        }

        return $abbreviation;
    }
}
