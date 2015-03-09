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
 * Checks that a field value has a given prefix.
 *
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class StartsWith extends Atom
{
    /**
     * @var string
     */
    private $prefix;

    /**
     * Creates the criterion.
     *
     * @param string $field  The field name.
     * @param string $prefix The prefix.
     */
    public function __construct($field, $prefix)
    {
        parent::__construct($field);

        $this->prefix = $prefix;
    }

    /**
     * {@inheritdoc}
     */
    protected function matchValue($value)
    {
        return 0 === strpos($value, $this->prefix);
    }
}
