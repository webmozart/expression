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
        $this->disjuncts[] = $x;
    }

    public function orNot(Criteria $criteria)
    {
        $this->disjuncts[] = new Not($criteria);
    }

    public function orNull($field)
    {
        $this->disjuncts[] = new Null($field);
    }

    public function orNotNull($field)
    {
        $this->disjuncts[] = new NotNull($field);
    }

    public function orEmpty($field)
    {
        $this->disjuncts[] = new IsEmpty($field);
    }

    public function orNotEmpty($field)
    {
        $this->disjuncts[] = new NotEmpty($field);
    }

    public function orTrue($field, $strict = true)
    {
        $this->disjuncts[] = new True($field, $strict);
    }

    public function orFalse($field, $strict = true)
    {
        $this->disjuncts[] = new False($field, $strict);
    }

    public function orEquals($field, $value)
    {
        $this->disjuncts[] = new Equals($field, $value);
    }

    public function orNotEquals($field, $value)
    {
        $this->disjuncts[] = new NotEquals($field, $value);
    }

    public function orSame($field, $value)
    {
        $this->disjuncts[] = new Same($field, $value);
    }

    public function orNotSame($field, $value)
    {
        $this->disjuncts[] = new NotSame($field, $value);
    }

    public function orGreaterThan($field, $value)
    {
        $this->disjuncts[] = new GreaterThan($field, $value);
    }

    public function orGreaterThanEqual($field, $value)
    {
        $this->disjuncts[] = new GreaterThanEqual($field, $value);
    }

    public function orLessThan($field, $value)
    {
        $this->disjuncts[] = new LessThan($field, $value);
    }

    public function orLessThanEqual($field, $value)
    {
        $this->disjuncts[] = new LessThanEqual($field, $value);
    }

    public function orOneOf($field, array $values, $strict = true)
    {
        $this->disjuncts[] = new OneOf($field, $values, $strict);
    }

    public function orMatches($field, $regExp)
    {
        $this->disjuncts[] = new Matches($field, $regExp);
    }

    public function orStartsWith($field, $prefix)
    {
        $this->disjuncts[] = new StartsWith($field, $prefix);
    }

    public function orEndsWith($field, $suffix)
    {
        $this->disjuncts[] = new EndsWith($field, $suffix);
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
}
