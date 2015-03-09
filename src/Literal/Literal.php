<?php

/*
 * This file is part of the webmozart/criteria package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Criteria\Literal;

use Webmozart\Criteria\Criteria;

/**
 * A logical literal.
 *
 * A literal is any part of a formula that does not contain "and" and "or"
 * operators. In other words, a literal is an {@link Atom} or a negated
 * {@link Atom}.
 *
 * Examples:
 *
 *  * not endsWith(fileName, ".css")
 *  * greaterThan(age, 0)
 *
 * The following examples are *not* literals:
 *
 *  * greaterThan(age, 0) and lessThan(age, 120)
 *  * oneOf(category, ["A", "B", "C]) or null(category)
 *
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
abstract class Literal implements Criteria
{
}
