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
use Webmozart\Criteria\Atom\False;
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
use Webmozart\Criteria\Atom\True;
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

    public function andX(Criteria $x)
    {
        foreach ($this->conjuncts as $conjunct) {
            if ($conjunct->equals($x)) {
                return;
            }
        }

        $this->conjuncts[] = $x;
    }

    public function andNot(Criteria $criteria)
    {
        $this->andX(new Not($criteria));
    }

    public function andNull($field)
    {
        $this->andX(new Null($field));
    }

    public function andNotNull($field)
    {
        $this->andX(new NotNull($field));
    }

    public function andEmpty($field)
    {
        $this->andX(new IsEmpty($field));
    }

    public function andNotEmpty($field)
    {
        $this->andX(new NotEmpty($field));
    }

    public function andTrue($field, $strict = true)
    {
        $this->andX(new True($field, $strict));
    }

    public function andFalse($field, $strict = true)
    {
        $this->andX(new False($field, $strict));
    }

    public function andEquals($field, $value)
    {
        $this->andX(new Equals($field, $value));
    }

    public function andNotEquals($field, $value)
    {
        $this->andX(new NotEquals($field, $value));
    }

    public function andSame($field, $value)
    {
        $this->andX(new Same($field, $value));
    }

    public function andNotSame($field, $value)
    {
        $this->andX(new NotSame($field, $value));
    }

    public function andGreaterThan($field, $value)
    {
        $this->andX(new GreaterThan($field, $value));
    }

    public function andGreaterThanEqual($field, $value)
    {
        $this->andX(new GreaterThanEqual($field, $value));
    }

    public function andLessThan($field, $value)
    {
        $this->andX(new LessThan($field, $value));
    }

    public function andLessThanEqual($field, $value)
    {
        $this->andX(new LessThanEqual($field, $value));
    }

    public function andOneOf($field, array $values, $strict = true)
    {
        $this->andX(new OneOf($field, $values, $strict));
    }

    public function andMatches($field, $regExp)
    {
        $this->andX(new Matches($field, $regExp));
    }

    public function andStartsWith($field, $prefix)
    {
        $this->andX(new StartsWith($field, $prefix));
    }

    public function andEndsWith($field, $suffix)
    {
        $this->andX(new EndsWith($field, $suffix));
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

    /**
     * {@inheritdoc}
     */
    public function equals(Criteria $other)
    {
        if (get_class($this) !== get_class($other)) {
            return false;
        }

        /** @var static $other */
        $leftConjuncts = $this->conjuncts;
        $rightConjuncts = $other->conjuncts;

        foreach ($leftConjuncts as $leftConjunct) {
            foreach ($rightConjuncts as $j => $rightConjunct) {
                if ($leftConjunct->equals($rightConjunct)) {
                    unset($rightConjuncts[$j]);
                    continue 2;
                }
            }

            // $leftConjunct was not found in $rightConjuncts
            return false;
        }

        // All $leftConjuncts were found. Check if any $rightConjuncts are left
        return 0 === count($rightConjuncts);
    }
}
