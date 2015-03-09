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
    protected $field;

    /**
     * Creates the atom.
     *
     * @param string $field The name of the field to test.
     */
    public function __construct($field)
    {
        $this->field = $field;
    }

    /**
     * {@inheritdoc}
     */
    public function match(array $values)
    {
        if (!array_key_exists($this->field, $values)) {
            throw new LogicException(sprintf(
                'Cannot evaluate criterion: The field "%s" is missing.',
                $this->field
            ));
        }

        return $this->matchValue($values[$this->field]);
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
