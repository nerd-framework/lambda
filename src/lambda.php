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
        return lambda($pattern);
    } else {
        return lambdaIndexed($pattern);
    }
}

/**
 * Whether $pattern uses unindexed placeholders.
 *
 * @param string $pattern
 * @return int
 */
function isUnindexed($pattern)
{
    return preg_match('~_(?!\d+)~', $pattern);
}

/**
 * Whether $pattern uses indexed placeholders.
 *
 * @param string $pattern
 * @return int
 */
function isIndexed($pattern)
{
    return preg_match('~_\d+~', $pattern);
}

/**
 * Creates function from unindexed pattern.
 *
 * @param string $pattern
 * @return string
 */
function lambda($pattern)
{
    $index = 0;
    $args = [];

    $pattern = preg_replace_callback('~(_)(?!\d+)~', function () use (&$index, &$args) {
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

    $pattern = preg_replace_callback('~(_(\d+))~', function ($match) use (&$index, &$args) {
        $arg = '$arg' . $match[2];
        array_push($args, $arg);
        return $arg;
    }, $pattern);

    return create_function(implode(', ', array_unique($args)), "return $pattern;");
}
