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
 * Checks that a value is not empty.
 *
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class NotEmpty extends Literal
{
    /**
     * {@inheritdoc}
     */
    public function evaluate($value)
    {
        return !empty($value);
    }

    /**
     * {@inheritdoc}
     */
    public function toString()
    {
        return 'notEmpty()';
    }
}
