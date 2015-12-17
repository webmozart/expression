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
use Webmozart\Expression\Util\StringUtil;

/**
 * Checks that a value has a given suffix.
 *
 * @since  1.0
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class EndsWith extends Literal
{
    /**
     * @var string
     */
    private $acceptedSuffix;

    /**
     * Creates the expression.
     *
     * @param string $acceptedSuffix The accepted suffix.
     */
    public function __construct($acceptedSuffix)
    {
        $this->acceptedSuffix = (string) $acceptedSuffix;
    }

    /**
     * Returns the accepted suffix.
     *
     * @return string The accepted suffix.
     */
    public function getAcceptedSuffix()
    {
        return $this->acceptedSuffix;
    }

    /**
     * {@inheritdoc}
     */
    public function evaluate($value)
    {
        return $this->acceptedSuffix === substr($value, -strlen($this->acceptedSuffix));
    }

    /**
     * {@inheritdoc}
     */
    public function equivalentTo(Expression $other)
    {
        // Since this class is final, we can check with instanceof
        return $other instanceof $this && $this->acceptedSuffix === $other->acceptedSuffix;
    }

    /**
     * {@inheritdoc}
     */
    public function toString()
    {
        return 'endsWith('.StringUtil::formatValue($this->acceptedSuffix).')';
    }
}
