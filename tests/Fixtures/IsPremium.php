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

use Webmozart\Expression\Expr;
use Webmozart\Expression\Selector\Method;

class IsPremium extends Method
{
    public function __construct()
    {
        parent::__construct('isPremium', array(), Expr::same(true));
    }
}
