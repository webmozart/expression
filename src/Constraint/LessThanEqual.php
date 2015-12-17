<?php

/*
 * This file is part of the webmozart/expression package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Expression\Constraint;

use Webmozart\Expression\Expression;
use Webmozart\Expression\Logic\Literal;
use Webmozart\Expression\Util\StringUtil;

/**
 * Checks that a value is less than or equal to a given value.
 *
 * The comparison is done using PHP's "<=" comparison operator.
 *
 * @since  1.0
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class LessThanEqual extends Literal
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
        return $value <= $this->comparedValue;
    }

    /**
     * {@inheritdoc}
     */
    public function equivalentTo(Expression $other)
    {
        // Since this class is final, we can check with instanceof
        return $other instanceof $this && $this->comparedValue == $other->comparedValue;
    }

    /**
     * {@inheritdoc}
     */
    public function toString()
    {
        return '<='.StringUtil::formatValue($this->comparedValue);
    }
}
