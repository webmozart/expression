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
 * Checks that a field value is one of a list of values.
 *
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class OneOf extends Atom
{
    /**
     * @var array
     */
    private $values;

    /**
     * @var bool
     */
    private $strict;

    /**
     * Creates the criterion.
     *
     * @param string $field  The field name.
     * @param array  $values The accepted value.
     * @param bool   $strict Whether to do strict comparison.
     */
    public function __construct($field, array $values, $strict = true)
    {
        parent::__construct($field);

        $this->values = $values;
        $this->strict = $strict;
    }

    /**
     * {@inheritdoc}
     */
    protected function matchValue($value)
    {
        return in_array($value, $this->values, $this->strict);
    }
}
