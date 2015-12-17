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
 * Checks that a value matches a given regular expression.
 *
 * The comparison is done using PHP's `preg_match()` function.
 *
 * @since  1.0
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class Matches extends Literal
{
    /**
     * @var string
     */
    private $regExp;

    /**
     * Creates the expression.
     *
     * @param string $regExp The regular expression.
     */
    public function __construct($regExp)
    {
        $this->regExp = $regExp;
    }

    /**
     * Returns the regular expression.
     *
     * @return mixed The regular expression.
     */
    public function getRegularExpression()
    {
        return $this->regExp;
    }

    /**
     * {@inheritdoc}
     */
    public function evaluate($value)
    {
        return (bool) preg_match($this->regExp, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function equivalentTo(Expression $other)
    {
        // Since this class is final, we can check with instanceof
        return $other instanceof $this && $this->regExp === $other->regExp;
    }

    /**
     * {@inheritdoc}
     */
    public function toString()
    {
        return 'matches('.StringUtil::formatValue($this->regExp).')';
    }
}
