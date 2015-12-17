<?php

/*
 * This file is part of the vendor/project package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Expression\Tests\Fixtures;

class Customer
{
    private $premium = false;

    private $bookings = array();

    public function setPremium($premium)
    {
        $this->premium = (bool) $premium;
    }

    public function isPremium()
    {
        return $this->premium;
    }

    public function setBookings(array $bookings)
    {
        $this->bookings = $bookings;
    }

    public function getBookings()
    {
        return $this->bookings;
    }
}
