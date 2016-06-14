<?php

namespace Lambda;

/**
 * Creates function using $pattern.
 *
 * @param string $pattern
 * @return callable
 * @throws \Exception
 */
function l($pattern)
{
    $isUnindexed = isUnindexed($pattern);
    $isIndexed = isIndexed($pattern);

    if ($isUnindexed && $isIndexed) {
        throw new \Exception("Mixing of indexed and unindexed placeholders is not supported.");
    } elseif ($isUnindexed) {
        return lambdaUnindexed($pattern);
    }

    return lambdaIndexed($pattern);
}

/**
 * Whether $pattern uses unindexed placeholders.
 *
 * @param string $pattern
 * @return int
 */
function isUnindexed($pattern)
{
    return preg_match('~\$(?!\d+)~', $pattern);
}

/**
 * Whether $pattern uses indexed placeholders.
 *
 * @param string $pattern
 * @return int
 */
function isIndexed($pattern)
{
    return preg_match('~\$\d+~', $pattern);
}

/**
 * Creates function from unindexed pattern.
 *
 * @param string $pattern
 * @return string
 */
function lambdaUnindexed($pattern)
{
    $index = 0;
    $args = [];

    $pattern = preg_replace_callback('~(\$)(?!\d+)~', function () use (&$index, &$args) {
        $arg = '$arg' . $index ++;
        array_push($args, $arg);
        return $arg;
    }, $pattern);

    return create_function(implode(', ', $args), "return $pattern;");
}

/**
 * Creates function from indexed pattern.
 *
 * @param $pattern
 * @return string
 */
function lambdaIndexed($pattern)
{
    $args = [];

    $pattern = preg_replace_callback('~(\$(\d+))~', function ($match) use (&$index, &$args) {
        $arg = '$arg' . $match[2];
        array_push($args, $arg);
        return $arg;
    }, $pattern);

    return create_function(implode(', ', array_unique($args)), "return $pattern;");
}
