<?php

/*
 * This file is part of the webmozart/criteria package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Criteria\Comparison;

use Webmozart\Criteria\Logic\Literal;

/**
 * Checks that a value matches a given regular expression.
 *
 * The comparison is done using PHP's `preg_match()` function.
 *
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class Matches extends Literal
{
    /**
     * @var string
     */
    private $regExp;

    /**
     * Creates the criterion.
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
    public function match($value)
    {
        return (bool) preg_match($this->regExp, $value);
    }
}
