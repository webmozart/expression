<?php

/*
 * This file is part of the webmozart/expression package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Expression\Comparison;

use Webmozart\Expression\Logic\Literal;

/**
 * Checks that a value has a given prefix.
 *
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class StartsWith extends Literal
{
    /**
     * @var string
     */
    private $acceptedPrefix;

    /**
     * Creates the expression.
     *
     * @param string $acceptedPrefix The accepted prefix.
     */
    public function __construct($acceptedPrefix)
    {
        $this->acceptedPrefix = $acceptedPrefix;
    }

    /**
     * Returns the accepted prefix.
     *
     * @return string The accepted prefix.
     */
    public function getAcceptedPrefix()
    {
        return $this->acceptedPrefix;
    }

    /**
     * {@inheritdoc}
     */
    public function evaluate($value)
    {
        return 0 === strpos($value, $this->acceptedPrefix);
    }
}
