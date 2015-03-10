<?php

/*
 * This file is part of the webmozart/criteria package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Expression\Comparison;

use Webmozart\Expression\Logic\Literal;

/**
 * Checks that a value is not identical to another value.
 *
 * The comparison is done using PHP's "!==" equality operator.
 *
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class NotSame extends Literal
{
    /**
     * @var mixed
     */
    private $comparedValue;

    /**
     * Creates the expression.
     *
     * @param mixed $comparedValue The compared value.
     */
    public function __construct($comparedValue)
    {
        $this->comparedValue = $comparedValue;
    }

    /**
     * Returns the compared value.
     *
     * @return mixed The compared value.
     */
    public function getComparedValue()
    {
        return $this->comparedValue;
    }

    /**
     * {@inheritdoc}
     */
    public function evaluate($value)
    {
        return $this->comparedValue !== $value;
    }
}
