<?php

/*
 * This file is part of the webmozart/criteria package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Criteria\Formula;

use Webmozart\Criteria\Atom\EndsWith;
use Webmozart\Criteria\Atom\Equals;
use Webmozart\Criteria\Atom\GreaterThan;
use Webmozart\Criteria\Atom\GreaterThanEqual;
use Webmozart\Criteria\Atom\IsEmpty;
use Webmozart\Criteria\Atom\LessThan;
use Webmozart\Criteria\Atom\LessThanEqual;
use Webmozart\Criteria\Atom\Matches;
use Webmozart\Criteria\Atom\NotEmpty;
use Webmozart\Criteria\Atom\NotEquals;
use Webmozart\Criteria\Atom\NotNull;
use Webmozart\Criteria\Atom\NotSame;
use Webmozart\Criteria\Atom\Null;
use Webmozart\Criteria\Atom\OneOf;
use Webmozart\Criteria\Atom\Same;
use Webmozart\Criteria\Atom\StartsWith;
use Webmozart\Criteria\Criteria;
use Webmozart\Criteria\Literal\Not;

/**
 * A disjunction of criteria.
 *
 * A disjunction is a set of {@link Criteria} instances connected by logical
 * "and" operators.
 *
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class Conjunction implements Criteria
{
    /**
     * @var Criteria[]
     */
    private $conjuncts;

    /**
     * Creates a conjunction of the given criteria.
     *
     * @param Criteria[] $conjuncts The conjuncts.
     */
    public function __construct(array $conjuncts = array())
    {
        $this->conjuncts = $conjuncts;
    }

    /**
     * Returns the conjuncts of the conjunction.
     *
     * @return Criteria[] The conjuncts.
     */
    public function getConjuncts()
    {
        return $this->conjuncts;
    }

    public function andCriteria(Criteria $criteria)
    {
        $this->conjuncts[] = $criteria;
    }

    public function andNot(Criteria $criteria)
    {
        $this->conjuncts[] = new Not($criteria);
    }

    public function andNull($field)
    {
        $this->conjuncts[] = new Null($field);
    }

    public function andNotNull($field)
    {
        $this->conjuncts[] = new NotNull($field);
    }

    public function andEmpty($field)
    {
        $this->conjuncts[] = new IsEmpty($field);
    }

    public function andNotEmpty($field)
    {
        $this->conjuncts[] = new NotEmpty($field);
    }

    public function andEquals($field, $value)
    {
        $this->conjuncts[] = new Equals($field, $value);
    }

    public function andNotEquals($field, $value)
    {
        $this->conjuncts[] = new NotEquals($field, $value);
    }

    public function andSame($field, $value)
    {
        $this->conjuncts[] = new Same($field, $value);
    }

    public function andNotSame($field, $value)
    {
        $this->conjuncts[] = new NotSame($field, $value);
    }

    public function andGreaterThan($field, $value)
    {
        $this->conjuncts[] = new GreaterThan($field, $value);
    }

    public function andGreaterThanEqual($field, $value)
    {
        $this->conjuncts[] = new GreaterThanEqual($field, $value);
    }

    public function andLessThan($field, $value)
    {
        $this->conjuncts[] = new LessThan($field, $value);
    }

    public function andLessThanEqual($field, $value)
    {
        $this->conjuncts[] = new LessThanEqual($field, $value);
    }

    public function andOneOf($field, array $values, $strict = true)
    {
        $this->conjuncts[] = new OneOf($field, $values, $strict);
    }

    public function andMatches($field, $regExp)
    {
        $this->conjuncts[] = new Matches($field, $regExp);
    }

    public function andStartsWith($field, $prefix)
    {
        $this->conjuncts[] = new StartsWith($field, $prefix);
    }

    public function andEndsWith($field, $suffix)
    {
        $this->conjuncts[] = new EndsWith($field, $suffix);
    }

    /**
     * {@inheritdoc}
     */
    public function match(array $values)
    {
        foreach ($this->conjuncts as $criteria) {
            if (!$criteria->match($values)) {
                return false;
            }
        }

        return true;
    }
}
