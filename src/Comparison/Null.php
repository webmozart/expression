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
 * Checks that a value is null.
 *
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class Null extends Literal
{
    /**
     * {@inheritdoc}
     */
    public function match($value)
    {
        return null === $value;
    }
}
