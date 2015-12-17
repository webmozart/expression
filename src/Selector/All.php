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

use Traversable;

/**
 * Checks that all iterator entries match an expression.
 *
 * @since  1.0
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class All extends Selector
{
    /**
     * {@inheritdoc}
     */
    public function evaluate($value)
    {
        if (!is_array($value) && !$value instanceof Traversable) {
            return false;
        }

        foreach ($value as $entry) {
            if (!$this->expr->evaluate($entry)) {
                return false;
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function toString()
    {
        return 'all('.$this->expr->toString().')';
    }
}
