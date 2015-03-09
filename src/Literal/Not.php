<?php

/*
 * This file is part of the webmozart/criteria package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Criteria\Literal;

use Webmozart\Criteria\Criteria;

/**
 * Negates some criteria.
 *
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class Not extends Literal
{
    /**
     * @var Criteria
     */
    private $negatedCriteria;

    /**
     * Creates the negation.
     *
     * @param Criteria $criteria The negated criteria.
     */
    public function __construct(Criteria $criteria)
    {
        $this->negatedCriteria = $criteria;
    }

    /**
     * {@inheritdoc}
     */
    public function match(array $values)
    {
        return !$this->negatedCriteria->match($values);
    }

    /**
     * {@inheritdoc}
     */
    public function equals(Criteria $other)
    {
        if (get_class($this) !== get_class($other)) {
            return false;
        }

        /** @var Not $other */
        return $this->negatedCriteria->equals($other->negatedCriteria);
    }
}
