<?php

/*
 * This file is part of the webmozart/criteria package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Criteria;

/**
 * Criteria that can be matched against a value.
 *
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
interface Criteria
{
    /**
     * Evaluates the criteria with the given value.
     *
     * @param mixed $value A value.
     *
     * @return bool Returns `true` if the value satisfies the criteria and
     *              `false` otherwise.
     */
    public function match($value);

    /**
     * Returns whether this criteria is logically equivalent to other criteria.
     *
     * @param Criteria $other Some criteria.
     *
     * @return bool Returns `true` if the criteria are logically equivalent and
     *              `false` otherwise.
     */
    public function equals(Criteria $other);
}
