<?php

/*
 * This file is part of the webmozart/criteria package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Criteria\Tests\Literal\Fixtures;

use Webmozart\Criteria\Literal\Literal;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class TestLiteral extends Literal
{
    private $field;

    public function __construct($field = null)
    {
        $this->field = $field;
    }

    public function match(array $values)
    {
    }
}
