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
 * Checks that a value has a given suffix.
 *
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class EndsWith extends Literal
{
    /**
     * @var string
     */
    private $acceptedSuffix;

    /**
     * Creates the criterion.
     *
     * @param string $acceptedSuffix The accepted suffix.
     */
    public function __construct($acceptedSuffix)
    {
        $this->acceptedSuffix = $acceptedSuffix;
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
    public function match($value)
    {
        return $this->acceptedSuffix === substr($value, -strlen($this->acceptedSuffix));
    }
}
