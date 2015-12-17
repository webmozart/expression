<?php

/*
 * This file is part of the vendor/project package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Expression\Tests;

use PHPUnit_Framework_TestCase;
use Webmozart\Expression\Expr;
use Webmozart\Expression\Tests\Fixtures\Customer;
use Webmozart\Expression\Tests\Fixtures\HasPreviousBookings;
use Webmozart\Expression\Tests\Fixtures\IsPremium;

/**
 * @since  1.0
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class DomainExpressionsTest extends PHPUnit_Framework_TestCase
{
    public function testDomainExpressions()
    {
        $c1 = new Customer();
        $c1->setPremium(true);
        $c2 = new Customer();
        $c2->setBookings(array('booking1', 'booking2'));
        $c3 = new Customer();
        $c3->setPremium(true);
        $c3->setBookings(array('booking1'));

        $customers = array($c1, $c2, $c3);

        $this->assertEquals(array($c1, 2 => $c3), Expr::filter($customers, new IsPremium()));
        $this->assertEquals(array(1 => $c2, 2 => $c3), Expr::filter($customers, new HasPreviousBookings()));
        $this->assertEquals(array(2 => $c3), Expr::filter($customers, Expr::andX(array(
            new HasPreviousBookings(),
            new IsPremium(),
        ))));
    }
}
