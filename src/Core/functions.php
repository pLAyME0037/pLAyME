<?php

declare(strict_types=1);

namespace Core;

/**
 * Curries a function.
 *
 * @param callable $fn The function to curry.
 * @return callable The curried function.
 */
function curry(callable $fn): callable
{
    $reflection = new \ReflectionFunction($fn);
    $numArgs = $reflection->getNumberOfRequiredParameters();

    $curryRec = function (array $curriedArgs = []) use ($fn, $numArgs, $reflection, &$curryRec) {
        return function (...$args) use ($fn, $numArgs, $reflection, $curriedArgs, &$curryRec) {
            $allArgs = array_merge($curriedArgs, $args);

            if (count($allArgs) >= $numArgs) {
                return $reflection->invokeArgs($allArgs);
            } else {
                return $curryRec($allArgs);
            }
        };
    };

    return $curryRec([]);
}

/**
 * Composes multiple functions from right to left.
 *
 * @param callable ...$fns Functions to compose.
 * @return callable The composed function.
 */
function compose(callable ...$fns): callable
{
    return array_reduce(
        array_reverse($fns),
        fn(callable $carry, callable $item) => fn(...$args) => $item($carry(...$args)),
        fn($x) => $x
    );
}

/**
 * Pipes a value through multiple functions from left to right.
 *
 * @param mixed $value The initial value.
 * @param callable ...$fns Functions to pipe the value through.
 * @return mixed The final result.
 */
function pipe(mixed $value, callable ...$fns): mixed
{
    return array_reduce($fns, fn(mixed $carry, callable $item) => $item($carry), $value);
}

/**
 * Applies a function to a value.
 *
 * @param callable $fn The function to apply.
 * @param mixed $value The value to apply the function to.
 * @return mixed The result of applying the function.
 */
function apply(callable $fn, mixed $value): mixed
{
    return $fn($value);
}

/**
 * Maps a function over a list.
 *
 * @param callable $fn The function to apply to each element.
 * @param array $list The list to map over.
 * @return array The new list with the function applied to each element.
 */
function map(callable $fn, array $list): array
{
    return array_map($fn, $list);
}

/**
 * Filters a list based on a predicate function.
 *
 * @param callable $predicate The predicate function.
 * @param array $list The list to filter.
 * @return array The filtered list.
 */
function filter(callable $predicate, array $list): array
{
    return array_filter($list, $predicate);
}

/**
 * Reduces a list to a single value using a reducer function.
 *
 * @param callable $reducer The reducer function.
 * @param mixed $initialValue The initial value for the reduction.
 * @param array $list The list to reduce.
 * @return mixed The reduced value.
 */
function reduce(callable $reducer, mixed $initialValue, array $list): mixed
{
    return array_reduce($list, $reducer, $initialValue);
}

/**
 * Creates a new array with the given element added to the end.
 *
 * @param mixed $element The element to add.
 * @param array $list The original array.
 * @return array The new array with the element added.
 */
function append(mixed $element, array $list): array
{
    return [...$list, $element];
}

/**
 * Creates a new array with the given element added to the beginning.
 *
 * @param mixed $element The element to add.
 * @param array $list The original array.
 * @return array The new array with the element added.
 */
function prepend(mixed $element, array $list): array
{
    return [$element, ...$list];
}

/**
 * Returns a new array with the element at the specified index removed.
 *
 * @param int $index The index of the element to remove.
 * @param array $list The original array.
 * @return array The new array with the element removed.
 */
function remove(int $index, array $list): array
{
    return array_values(array_filter($list, fn($k) => $k !== $index, ARRAY_FILTER_USE_KEY));
}

/**
 * Returns a new array with the element at the specified index updated.
 *
 * @param int $index The index of the element to update.
 * @param callable $updater A function that takes the old value and returns the new value.
 * @param array $list The original array.
 * @return array The new array with the element updated.
 */
function update(int $index, callable $updater, array $list): array
{
    $newList = [];
    foreach ($list as $key => $value) {
        if ($key === $index) {
            $newList[$key] = $updater($value);
        } else {
            $newList[$key] = $value;
        }
    }
    return $newList;
}

/**
 * Gets a value from an array by key.
 *
 * @param string|int $key The key to retrieve.
 * @param array $array The array to search.
 * @return mixed|null The value if found, otherwise null.
 */
function get(string|int $key, array $array): mixed
{
    return $array[$key] ?? null;
}

/**
 * Sets a value in an array by key.
 *
 * @param string|int $key The key to set.
 * @param mixed $value The value to set.
 * @param array $array The original array.
 * @return array The new array with the value set.
 */
function set(string|int $key, mixed $value, array $array): array
{
    $newArray = $array;
    $newArray[$key] = $value;
    return $newArray;
}

/**
 * Creates a Monad for error handling and chaining operations.
 *
 * @param mixed $value The initial value.
 * @return object An object representing the Monad.
 */
function monad(mixed $value): object
{
    $map = function (callable $fn) use ($value) {
        if ($value === null) {
            return monad(null);
        }
        return monad($fn($value));
    };

    $bind = function (callable $fn) use ($value) {
        if ($value === null) {
            return monad(null);
        }
        return $fn($value);
    };

    $get = fn() => $value;

    return (object) [
        'map' => $map,
        'bind' => $bind,
        'get' => $get,
    ];
}
