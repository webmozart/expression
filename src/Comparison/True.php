<?php

/*
 * This file is part of the webmozart/expression package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Expression\Comparison;

use Webmozart\Expression\Logic\Literal;

/**
 * Checks that a value is true.
 *
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class True extends Literal
{
    /**
     * @var bool
     */
    private $strict;

    /**
     * Creates the expression.
     *
     * @param bool $strict Whether to use strict comparison.
     */
    public function __construct($strict = true)
    {
        $this->strict = $strict;
    }

    /**
     * Returns whether the value is compared strictly.
     *
     * @return boolean Returns `true` if using strict comparison (`===`) and
     *                 `false` if using weak comparison (`==`).
     */
    public function isStrict()
    {
        return $this->strict;
    }

    /**
     * {@inheritdoc}
     */
    public function evaluate($value)
    {
        return $this->strict ? true === $value : (bool) $value;
    }
}
