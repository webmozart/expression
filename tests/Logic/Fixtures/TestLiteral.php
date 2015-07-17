<?php

/*
 * This file is part of the webmozart/expression package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Expression\Tests\Logic\Fixtures;

use Webmozart\Expression\Expression;
use Webmozart\Expression\Logic\Literal;

/**
 * @since  1.0
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class TestLiteral extends Literal
{
    private $value;

    public function __construct($value = null)
    {
        $this->value = $value;
    }

    public function evaluate($value)
    {
    }

    public function equivalentTo(Expression $other)
    {
        return $other instanceof $this && $this->value === $other->value;
    }

    public function toString()
    {
    }
}
