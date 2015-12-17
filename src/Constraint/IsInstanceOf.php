<?php

/*
 * This file is part of the webmozart/expression package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Expression\Constraint;

use Webmozart\Expression\Expression;
use Webmozart\Expression\Logic\Literal;

/**
 * Checks that a value is an instance of a given class name.
 *
 * @since  1.0
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class IsInstanceOf extends Literal
{
    /**
     * @var array
     */
    private $className;

    /**
     * Creates the expression.
     *
     * @param string $className The accepted class name.
     */
    public function __construct($className)
    {
        $this->className = $className;
    }

    /**
     * Returns the accepted class name.
     *
     * @return array The accepted class name.
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * {@inheritdoc}
     */
    public function evaluate($value)
    {
        return $value instanceof $this->className;
    }

    /**
     * {@inheritdoc}
     */
    public function equivalentTo(Expression $other)
    {
        // Since this class is final, we can check with instanceof
        if (!$other instanceof $this) {
            return false;
        }

        return $this->className === $other->className;
    }

    /**
     * {@inheritdoc}
     */
    public function toString()
    {
        return 'instanceOf('.$this->className.')';
    }
}
