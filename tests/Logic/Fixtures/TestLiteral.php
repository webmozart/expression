<?php

/*
 * This file is part of the webmozart/criteria package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Criteria\Tests\Logic\Fixtures;

use Webmozart\Criteria\Logic\Literal;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class TestLiteral extends Literal
{
    private $value;

    public function __construct($value = null)
    {
        $this->value = $value;
    }

    public function match($value)
    {
    }
}
