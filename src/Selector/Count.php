<?php

/*
 * This file is part of the webmozart/expression package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Expression\Selector;

use Countable;

/**
 * Checks that the count of a collection matches an expression.
 *
 * @since  1.0
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class Count extends Selector
{
    /**
     * {@inheritdoc}
     */
    public function evaluate($value)
    {
        if (!is_array($value) && !$value instanceof Countable) {
            return false;
        }

        return $this->expr->evaluate(count($value));
    }

    /**
     * {@inheritdoc}
     */
    public function toString()
    {
        return 'count('.$this->expr->toString().')';
    }
}
