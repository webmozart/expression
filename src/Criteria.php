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
 * Criteria that can be matched against a set of values.
 *
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
interface Criteria
{
    /**
     * Evaluates the criteria with the given values.
     *
     * @param array $values An array of values indexed by field names.
     *
     * @return bool Returns `true` if the values satisfy the criteria and
     *              `false` otherwise.
     */
    public function match(array $values);

    /**
     * Compares the criteria to other criteria.
     *
     * @param Criteria $other Some criteria.
     *
     * @return bool Returns `true` if the criteria are equal and `false`
     *              otherwise.
     */
    public function equals(Criteria $other);
}
