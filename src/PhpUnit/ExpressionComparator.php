<?php

/*
 * This file is part of the webmozart/criteria package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Expression\PhpUnit;

use SebastianBergmann\Comparator\ComparisonFailure;
use SebastianBergmann\Comparator\ObjectComparator;
use Webmozart\Expression\Expression;

/**
 * Compares {@link Expression} objects for equality.
 *
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class ExpressionComparator extends ObjectComparator
{
    /**
     * {@inheritdoc}
     */
    public function accepts($expected, $actual)
    {
        return $expected instanceof Expression && $actual instanceof Expression;
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

        /** @var Expression $actual */
        if (!$actual->equals($expected)) {
            // Let the parent comparator generate the error
            parent::assertEquals($expected, $actual, $delta, $canonicalize, $ignoreCase, $processed);
        }
    }
}
