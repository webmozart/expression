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
 * Checks that a field value has a given suffix.
 *
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class EndsWith extends Atom
{
    /**
     * @var string
     */
    private $suffix;

    /**
     * Creates the criterion.
     *
     * @param string $field  The field name.
     * @param string $suffix The suffix string.
     */
    public function __construct($field, $suffix)
    {
        parent::__construct($field);

        $this->suffix = $suffix;
    }

    /**
     * {@inheritdoc}
     */
    protected function matchValue($value)
    {
        return $this->suffix === substr($value, -strlen($this->suffix));
    }
}
