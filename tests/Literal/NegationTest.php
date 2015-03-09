<?php

/*
 * This file is part of the webmozart/criteria package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Criteria\Tests\Literal;

use PHPUnit_Framework_TestCase;
use Webmozart\Criteria\Atom\StartsWith;
use Webmozart\Criteria\Literal\Negation;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class NegationTest extends PHPUnit_Framework_TestCase
{
    public function testMatch()
    {
        $criterion = new Negation(new StartsWith('name', 'Thomas'));

        $this->assertTrue($criterion->match(array('name' => 'Mr. Thomas Edison')));
        $this->assertFalse($criterion->match(array('name' => 'Thomas Edison')));
    }
}
