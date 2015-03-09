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

use LogicException;
use Webmozart\Criteria\Literal\Literal;

/**
 * An atom is a part of a formula that does not contain any logical operators
 * ("and", "or", "not").
 *
 * Examples:
 *
 *  * endsWith(fileName, ".css")
 *  * greaterThan(age, 0)
 *
 * The following examples are *not* atoms:
 *
 *  * not greaterThan(age, 120)
 *  * greaterThan(age, 0) and lessThan(age, 120)
 *
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
abstract class Atom extends Literal
{
    /**
     * @var string
     */
    protected $fieldName;

    /**
     * Creates the atom.
     *
     * @param string $fieldName The name of the field to compare.
     */
    public function __construct($fieldName)
    {
        $this->fieldName = $fieldName;
    }

    /**
     * Returns the name of the compared field.
     *
     * @return string The name of the compared field.
     */
    public function getFieldName()
    {
        return $this->fieldName;
    }

    /**
     * {@inheritdoc}
     */
    public function match(array $values)
    {
        if (!array_key_exists($this->fieldName, $values)) {
            throw new LogicException(sprintf(
                'Cannot evaluate criterion: The field "%s" is missing.',
                $this->fieldName
            ));
        }

        return $this->matchValue($values[$this->fieldName]);
    }

    /**
     * Returns whether the atom evaluates to `true` with the given field value.
     *
     * @param mixed $value The field value.
     *
     * @return bool Returns `true` if the atom evaluates to `true` and `false`
     *              otherwise.
     */
    abstract protected function matchValue($value);
}
