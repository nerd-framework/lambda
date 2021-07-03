<?php

namespace Nerd\Lambda;

/**
 * Creates function using $pattern.
 *
 * @param string $pattern
 *
 * @throws \Exception
 *
 * @return callable
 */
function l(string $pattern): callable
{
    $isUnindexed = isUnindexed($pattern);
    $isIndexed = isIndexed($pattern);

    if ($isUnindexed && $isIndexed) {
        throw new \Exception('Mixing of indexed and unindexed placeholders is not supported.');
    } elseif ($isUnindexed) {
        return lambdaUnindexed($pattern);
    }

    return lambdaIndexed($pattern);
}

/**
 * Whether $pattern uses unindexed placeholders.
 *
 * @param string $pattern
 *
 * @return bool
 */
function isUnindexed(string $pattern): bool
{
    return (bool) preg_match('~\$(?!\d+)~', $pattern);
}

/**
 * Whether $pattern uses indexed placeholders.
 *
 * @param string $pattern
 *
 * @return bool
 */
function isIndexed(string $pattern): bool
{
    return (bool) preg_match('~\$\d+~', $pattern);
}

/**
 * Creates function from unindexed pattern.
 *
 * @param string $pattern
 *
 * @return string
 */
function lambdaUnindexed(string $pattern): string
{
    $index = 0;
    $args = [];
    $function_name = 'lambda_'.rand();

    $pattern = preg_replace_callback('~(\$)(?!\d+)~', function () use (&$index, &$args) {
        $arg = '$arg'.$index++;
        array_push($args, $arg);

        return $arg;
    }, $pattern);
    $args_pattern = implode(', ', $args);

    eval("function $function_name($args_pattern) { return ${pattern}; }");

    return $function_name;
}

/**
 * Creates function from indexed pattern.
 *
 * @param $pattern
 *
 * @return string
 */
function lambdaIndexed(string $pattern): string
{
    $args = [];

    $function_name = 'lambda_'.rand();
    $pattern = preg_replace_callback('~(\$(\d+))~', function ($match) use (&$index, &$args) {
        $arg = '$arg'.$match[2];
        array_push($args, $arg);

        return $arg;
    }, $pattern);
    $args_pattern = implode(', ', array_unique($args));

    eval("function $function_name($args_pattern) { return $pattern; }");

    return $function_name;
}
