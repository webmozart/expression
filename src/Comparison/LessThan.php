<?php

/*
 * This file is part of the webmozart/criteria package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Criteria\Comparison;

use Webmozart\Criteria\Logic\Literal;

/**
 * Checks that a value is less than a given value.
 *
 * The comparison is done using PHP's "<" comparison operator.
 *
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class LessThan extends Literal
{
    /**
     * @var mixed
     */
    private $comparedValue;

    /**
     * Creates the criterion.
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
    public function match($value)
    {
        return $value < $this->comparedValue;
    }
}
