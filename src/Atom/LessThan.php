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
 * Checks that a field value is less than a given value.
 *
 * The comparison is done using PHP's "<" comparison operator.
 *
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class LessThan extends Atom
{
    /**
     * @var mixed
     */
    private $comparedValue;

    /**
     * Creates the criterion.
     *
     * @param string $fieldName     The field name.
     * @param mixed  $comparedValue The compared value.
     */
    public function __construct($fieldName, $comparedValue)
    {
        parent::__construct($fieldName);

        $this->comparedValue = $comparedValue;
    }

    /**
     * Returns the compared value.
     *
     * @return mixed The compared value.
     */
    public function getComparedValue()
    {
        return $this->comparedValue;
    }

    /**
     * {@inheritdoc}
     */
    protected function matchValue($value)
    {
        return $value < $this->comparedValue;
    }
}
