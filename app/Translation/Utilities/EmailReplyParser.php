<?php

namespace App\Translation\Utilities;

/**
 * EmailReplyParser
 * Parses only the reply out of an email and ignores
 * quotes / original messages (all the text below
 * the reply-line).
 *
 * @package App\Translation\Utilities
 */
class EmailReplyParser
{
    /**
     * Email Line Regex
     * Parse everything until reply-line. Uses a negative look-ahead so that
     * if the reply-line doesn't exist, it will match everything else.
     * A positive look-ahead would match nothing.
     */
    const REPLY_LINE_REGEX = '/(?:(?!(\s+--- Please type your reply above this line ---))[\s\S])+/';

    /**
     * NOT IMPLEMENTED - Regex for matching email reply headers.
     * Usually added to replies by various clients.
     */
    const QUOTED_HEADERS_REGEX = [
        // On <date> at <time>, <name/email> wrote:
        '/^On \d+ \w+ \d+ at \d+:\d+:\d+ [apm]+,[\s\S]+?wrote:?/m',
        // ------------------ Original ------------------
        // From: <email>
        // Send time: <date>
        // To: <email>
        // Subject: <subject>
        '/------------------ Original ------------------[\s\S]+?\bSubject:.*\s+/m',
        // 在 <date>, <time>, <name> 写道：
        '/^(在[\S\s]+写道：)$/m',
        // <date> <time>、<name> のメッセージ:
        '/^(20[0-9]{2}\/.+のメッセージ:)$/m'
    ];

    /**
     * Parse reply only out of text.
     * @param $text
     * @return mixed
     */
    public static function parse($text)
    {
        preg_match(self::REPLY_LINE_REGEX, $text, $matches);
        return $matches[0];
    }

    // TODO(?) ::: Write method that cleans up / removes quoted headers

}
