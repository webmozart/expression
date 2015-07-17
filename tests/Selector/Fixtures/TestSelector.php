<?php

/*
 * This file is part of the webmozart/expression package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Expression\Tests\Selector\Fixtures;

use Webmozart\Expression\Expression;
use Webmozart\Expression\Selector\Selector;

/**
 * @since  1.0
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class TestSelector extends Selector
{
    /**
     * @var string|int
     */
    private $key;

    public function __construct($key, Expression $expr)
    {
        parent::__construct($expr);

        $this->key = $key;
    }

    /**
     * {@inheritdoc}
     */
    public function evaluate($value)
    {
        return $this->expr->evaluate($value[$this->key]);
    }

    /**
     * {@inheritdoc}
     */
    public function toString()
    {
    }
}
