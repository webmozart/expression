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
use Webmozart\Expression\Util\StringUtil;

/**
 * Checks whether the result of a method call matches an expression.
 *
 * @since  1.0
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class Method extends Selector
{
    /**
     * @var string
     */
    private $methodName;

    /**
     * @var array
     */
    private $arguments;

    /**
     * Creates the expression.
     *
     * @param string     $methodName The name of the method to call.
     * @param array      $arguments  The arguments to pass to the method.
     * @param Expression $expr       The expression to evaluate for the result.
     */
    public function __construct($methodName, array $arguments, Expression $expr)
    {
        parent::__construct($expr);

        $this->methodName = $methodName;
        $this->arguments = $arguments;
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
     * Returns the method arguments.
     *
     * @return array The method arguments.
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * {@inheritdoc}
     */
    public function evaluate($value)
    {
        if (!is_object($value)) {
            return false;
        }

        if (!method_exists($value, $this->methodName)) {
            return false;
        }

        return $this->expr->evaluate(call_user_func_array(array($value, $this->methodName), $this->arguments));
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
        return $this->methodName === $other->methodName && $this->arguments === $other->arguments;
    }

    /**
     * {@inheritdoc}
     */
    public function toString()
    {
        $exprString = $this->expr->toString();
        $argsString = implode(', ', StringUtil::formatValues($this->arguments));

        if ($this->expr instanceof AndX || $this->expr instanceof OrX) {
            return $this->methodName.'('.$argsString.'){'.$exprString.'}';
        }

        // Append "functions" with "."
        if (isset($exprString[0]) && ctype_alpha($exprString[0])) {
            return $this->methodName.'('.$argsString.').'.$exprString;
        }

        return $this->methodName.'('.$argsString.')'.$exprString;
    }
}
