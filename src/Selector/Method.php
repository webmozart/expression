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
use Webmozart\Expression\Logic\Conjunction;
use Webmozart\Expression\Logic\Disjunction;

/**
 * Checks whether the result of a method call matches an expression.
 *
 * @since  1.0
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
final class Method extends Selector
{
    /**
     * @var string
     */
    private $methodName;

    /**
     * Creates the expression.
     *
     * @param string     $methodName The name of the method to call.
     * @param Expression $expr       The expression to evaluate for the result.
     */
    public function __construct($methodName, Expression $expr)
    {
        parent::__construct($expr);

        $this->methodName = $methodName;
    }

    /**
     * Returns the method name.
     *
     * @return string The method name.
     */
    public function getMethodName()
    {
        return $this->methodName;
    }

    /**
     * {@inheritdoc}
     */
    public function evaluate($value)
    {
        if (!is_object($value)) {
            return false;
        }

        $methodName = $this->methodName;

        if (!method_exists($value, $methodName)) {
            return false;
        }

        return $this->expr->evaluate($value->$methodName());
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
        return $this->methodName == $other->methodName;
    }

    /**
     * {@inheritdoc}
     */
    public function toString()
    {
        $exprString = $this->expr->toString();

        if ($this->expr instanceof Conjunction || $this->expr instanceof Disjunction) {
            return $this->methodName.'(){'.$exprString.'}';
        }

        // Append "functions" with "."
        if (isset($exprString[0]) && ctype_alpha($exprString[0])) {
            return $this->methodName.'().'.$exprString;
        }

        return $this->methodName.'()'.$exprString;
    }
}
