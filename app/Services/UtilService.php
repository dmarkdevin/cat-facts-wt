<?php

namespace App\Services;

class UtilService
{

    public static function sanitizeData($data) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = sanitizeData($value);
            }
        } else {
            if (is_string($data)) {
                // For strings - remove HTML tags and sanitize
                $data = strip_tags($data); // Remove HTML tags
                $data = filter_var($data, FILTER_SANITIZE_STRING);
                $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
            } elseif (is_int($data)) {
                // For integers
                $data = filter_var($data, FILTER_SANITIZE_NUMBER_INT);
            } elseif (is_float($data)) {
                // For floating-point numbers
                $data = filter_var($data, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND);
            } else {
                // For other types (bool, object, etc.), just filter_var to sanitize
                $data = filter_var($data, FILTER_SANITIZE_STRING);
            }
        }
        return $data;
    }

    public static function truncateTextByCharacters($text, $charLimit = 30) {
        if (strlen($text) <= $charLimit) {
            return $text;
        } else {
            $truncatedText = substr($text, 0, $charLimit);
            $lastSpace = strrpos($truncatedText, ' ');

            // Ensures that the text is truncated at the nearest space and doesn't cut off a word
            if ($lastSpace !== false) {
                $truncatedText = substr($truncatedText, 0, $lastSpace);
            }

            return $truncatedText . '...';
        }
    }

    public static function standardDateTimeFormat($dateTime) {
                // Convert the date to a Unix timestamp
        // $timestamp = strtotime($dateTime);

        // Format the timestamp into the desired date format
        // return date('Y-m-d H:i:s', $timestamp);
        return $dateTime;
    }

}