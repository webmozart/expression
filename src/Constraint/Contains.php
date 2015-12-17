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
 * Checks that a value contains another value.
 *
 * @since  1.0
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 * @author Stephan Wentz <stephan@wentz.it>
 */
class Contains extends Literal
{
    /**
     * @var string
     */
    private $comparedValue;

    /**
     * Creates the expression.
     *
     * @param string $comparedValue The compared value.
     */
    public function __construct($comparedValue)
    {
        $this->comparedValue = (string) $comparedValue;
    }

    /**
     * Returns the accepted suffix.
     *
     * @return string The accepted suffix.
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
        return false !== strpos($value, $this->comparedValue);
    }

    /**
     * {@inheritdoc}
     */
    public function equivalentTo(Expression $other)
    {
        // Since this class is final, we can check with instanceof
        return $other instanceof $this && $this->comparedValue === $other->comparedValue;
    }

    /**
     * {@inheritdoc}
     */
    public function toString()
    {
        return 'contains('.StringUtil::formatValue($this->comparedValue).')';
    }
}
