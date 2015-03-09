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
 * Checks that a field value is true.
 *
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class True extends Atom
{
    /**
     * @var bool
     */
    private $strict;

    /**
     * Creates the criterion.
     *
     * @param string $field  The field name.
     * @param bool   $strict Whether to use strict comparison.
     */
    public function __construct($field, $strict = true)
    {
        parent::__construct($field);

        $this->strict = $strict;
    }

    /**
     * {@inheritdoc}
     */
    protected function matchValue($value)
    {
        return $this->strict ? true === $value : (bool) $value;
    }
}
