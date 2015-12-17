<?php

/*
 * This file is part of the webmozart/expression package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Expression\Selector;

use Webmozart\Expression\Expression;
use Webmozart\Expression\Logic\AndX;
use Webmozart\Expression\Logic\OrX;

/**
 * Checks whether the value of a property matches an expression.
 *
 * @since  1.0
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class Property extends Selector
{
    /**
     * @var string
     */
    private $propertyName;

    /**
     * Creates the expression.
     *
     * @param string     $propertyName The name of the property.
     * @param Expression $expr         The expression to evaluate for the result.
     */
    public function __construct($propertyName, Expression $expr)
    {
        parent::__construct($expr);

        $this->propertyName = $propertyName;
    }

    /**
     * Returns the property name.
     *
     * @return string The property name.
     */
    public function getPropertyName()
    {
        return $this->propertyName;
    }

    /**
     * {@inheritdoc}
     */
    public function evaluate($value)
    {
        if (!is_object($value)) {
            return false;
        }

        $propertyName = $this->propertyName;

        if (!property_exists($value, $propertyName)) {
            return false;
        }

        return $this->expr->evaluate($value->$propertyName);
    }

    /**
     * {@inheritdoc}
     */
    public function equivalentTo(Expression $other)
    {
        if (!parent::equivalentTo($other)) {
            return false;
        }

        /* @var static $other */
        return $this->propertyName === $other->propertyName;
    }

    /**
     * {@inheritdoc}
     */
    public function toString()
    {
        $exprString = $this->expr->toString();

        if ($this->expr instanceof AndX || $this->expr instanceof OrX) {
            return $this->propertyName.'{'.$exprString.'}';
        }

        // Append "functions" with "."
        if (isset($exprString[0]) && ctype_alpha($exprString[0])) {
            return $this->propertyName.'.'.$exprString;
        }

        return $this->propertyName.$exprString;
    }
}
