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
use Webmozart\Expression\Expression;

/**
 * Checks that at least N iterator entries match an expression.
 *
 * @since  1.0
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class AtLeast extends Selector
{
    /**
     * @var int
     */
    private $count;

    /**
     * Creates the expression.
     *
     * @param int        $count The minimum number of entries that must match
     *                          the expression.
     * @param Expression $expr  The expression to evaluate with each entry.
     */
    public function __construct($count, Expression $expr)
    {
        parent::__construct($expr);

        $this->count = (int) $count;
    }

    /**
     * {@inheritdoc}
     */
    public function evaluate($value)
    {
        if (!is_array($value) && !$value instanceof Traversable) {
            return false;
        }

        $found = 0;

        foreach ($value as $entry) {
            if ($this->expr->evaluate($entry)) {
                ++$found;

                if ($found >= $this->count) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function equivalentTo(Expression $other)
    {
        if (!parent::equivalentTo($other)) {
            return false;
        }

        /* @var static $other */
        return $this->count === $other->count;
    }

    /**
     * {@inheritdoc}
     */
    public function toString()
    {
        return 'atLeast('.$this->count.', '.$this->expr->toString().')';
    }
}
