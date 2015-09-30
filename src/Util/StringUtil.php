<?php

/*
 * This file is part of the webmozart/expression package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Expression\Util;

/**
 * Contains string utility methods.
 *
 * @since  1.0
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class StringUtil
{
    /**
     * Formats a value as string.
     *
     * @param mixed $value The value.
     *
     * @return string The value as string.
     */
    public static function formatValue($value)
    {
        if (null === $value) {
            return 'null';
        }

        if (true === $value) {
            return 'true';
        }

        if (false === $value) {
            return 'false';
        }

        if (is_string($value)) {
            return '"'.$value.'"';
        }

        if (is_object($value)) {
            return 'object';
        }

        if (is_array($value)) {
            return 'array';
        }

        return (string) $value;
    }

    /**
     * Formats a list of values as strings.
     *
     * @param array $values The values.
     *
     * @return array The values as strings.
     */
    public static function formatValues(array $values)
    {
        foreach ($values as $key => $value) {
            $values[$key] = self::formatValue($value);
        }

        return $values;
    }

    /**
     * May not be instantiated.
     */
    private function __construct()
    {
    }
}
