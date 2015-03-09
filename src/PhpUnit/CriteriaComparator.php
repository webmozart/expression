<?php

/*
 * This file is part of the webmozart/criteria package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Criteria\PhpUnit;

use SebastianBergmann\Comparator\ComparisonFailure;
use SebastianBergmann\Comparator\Factory;
use SebastianBergmann\Comparator\ObjectComparator;
use Webmozart\Criteria\Criteria;

/**
 * Compares {@link Criteria} objects for equality.
 *
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class CriteriaComparator extends ObjectComparator
{
    /**
     * @var bool
     */
    private static $registered = false;

    /**
     * Registers the comparator with PHPUnit.
     *
     * You should call this method in the `setUpBeforeClass()` method of all
     * test cases that compare {@link Criteria} instances.
     */
    public static function register()
    {
        if (!self::$registered) {
            self::$registered = true;
            Factory::getInstance()->register(new static());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function accepts($expected, $actual)
    {
        return $expected instanceof Criteria && $actual instanceof Criteria;
    }

    /**
     * {@inheritdoc}
     */
    public function assertEquals($expected, $actual, $delta = 0.0, $canonicalize = false, $ignoreCase = false, array &$processed = array())
    {
        if (get_class($actual) !== get_class($expected)) {
            throw new ComparisonFailure(
                $expected,
                $actual,
                $this->exporter->export($expected),
                $this->exporter->export($actual),
                false,
                sprintf(
                    '%s is not instance of expected class "%s".',
                    $this->exporter->export($actual),
                    get_class($expected)
                )
            );
        }

        /** @var Criteria $actual */
        if (!$actual->equals($expected)) {
            // Let the parent comparator generate the error
            parent::assertEquals($expected, $actual, $delta, $canonicalize, $ignoreCase, $processed);
        }
    }
}
