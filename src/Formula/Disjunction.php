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
 * "or" operators.
 *
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class Disjunction implements Criteria
{
    /**
     * @var Criteria[]
     */
    private $disjuncts;

    /**
     * Creates a disjunction of the given criteria.
     *
     * @param Criteria[] $disjuncts The disjuncts.
     */
    public function __construct(array $disjuncts = array())
    {
        $this->disjuncts = $disjuncts;
    }

    /**
     * Returns the disjuncts of the disjunction.
     *
     * @return Criteria[] The disjuncts.
     */
    public function getDisjuncts()
    {
        return $this->disjuncts;
    }

    public function orX(Criteria $x)
    {
        foreach ($this->disjuncts as $disjunct) {
            if ($disjunct->equals($x)) {
                return;
            }
        }

        $this->disjuncts[] = $x;
    }

    public function orNot(Criteria $criteria)
    {
        $this->orX(new Not($criteria));
    }

    public function orNull($field)
    {
        $this->orX(new Null($field));
    }

    public function orNotNull($field)
    {
        $this->orX(new NotNull($field));
    }

    public function orEmpty($field)
    {
        $this->orX(new IsEmpty($field));
    }

    public function orNotEmpty($field)
    {
        $this->orX(new NotEmpty($field));
    }

    public function orTrue($field, $strict = true)
    {
        $this->orX(new True($field, $strict));
    }

    public function orFalse($field, $strict = true)
    {
        $this->orX(new False($field, $strict));
    }

    public function orEquals($field, $value)
    {
        $this->orX(new Equals($field, $value));
    }

    public function orNotEquals($field, $value)
    {
        $this->orX(new NotEquals($field, $value));
    }

    public function orSame($field, $value)
    {
        $this->orX(new Same($field, $value));
    }

    public function orNotSame($field, $value)
    {
        $this->orX(new NotSame($field, $value));
    }

    public function orGreaterThan($field, $value)
    {
        $this->orX(new GreaterThan($field, $value));
    }

    public function orGreaterThanEqual($field, $value)
    {
        $this->orX(new GreaterThanEqual($field, $value));
    }

    public function orLessThan($field, $value)
    {
        $this->orX(new LessThan($field, $value));
    }

    public function orLessThanEqual($field, $value)
    {
        $this->orX(new LessThanEqual($field, $value));
    }

    public function orOneOf($field, array $values, $strict = true)
    {
        $this->orX(new OneOf($field, $values, $strict));
    }

    public function orMatches($field, $regExp)
    {
        $this->orX(new Matches($field, $regExp));
    }

    public function orStartsWith($field, $prefix)
    {
        $this->orX(new StartsWith($field, $prefix));
    }

    public function orEndsWith($field, $suffix)
    {
        $this->orX(new EndsWith($field, $suffix));
    }

    /**
     * {@inheritdoc}
     */
    public function match(array $values)
    {
        foreach ($this->disjuncts as $criteria) {
            if ($criteria->match($values)) {
                return true;
            }
        }

        return false;
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
        $leftDisjuncts = $this->disjuncts;
        $rightDisjuncts = $other->disjuncts;

        foreach ($leftDisjuncts as $leftDisjunct) {
            foreach ($rightDisjuncts as $j => $rightDisjunct) {
                if ($leftDisjunct->equals($rightDisjunct)) {
                    unset($rightDisjuncts[$j]);
                    continue 2;
                }
            }

            // $leftDisjunct was not found in $rightDisjuncts
            return false;
        }

        // All $leftDisjuncts were found. Check if any $rightDisjuncts are left
        return 0 === count($rightDisjuncts);
    }
}
