<?php

/*
 * This file is part of the webmozart/criteria package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Criteria\Atom;

/**
 * Checks that a field value is not identical to another value.
 *
 * The comparison is done using PHP's "!==" equality operator.
 *
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class NotSame extends Atom
{
    /**
     * @var mixed
     */
    private $value;

    /**
     * Creates the criterion.
     *
     * @param string $field The field name.
     * @param mixed  $comparedValue The compared value.
     */
    public function __construct($field, $comparedValue)
    {
        parent::__construct($field);

        $this->value = $comparedValue;
    }

    /**
     * {@inheritdoc}
     */
    protected function matchValue($value)
    {
        return $this->value !== $value;
    }
}
