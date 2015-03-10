<?php

/*
 * This file is part of the webmozart/criteria package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Criteria\Logic;

use Webmozart\Criteria\Comparison\EndsWith;
use Webmozart\Criteria\Comparison\Equals;
use Webmozart\Criteria\Comparison\False;
use Webmozart\Criteria\Comparison\GreaterThan;
use Webmozart\Criteria\Comparison\GreaterThanEqual;
use Webmozart\Criteria\Comparison\IsEmpty;
use Webmozart\Criteria\Comparison\LessThan;
use Webmozart\Criteria\Comparison\LessThanEqual;
use Webmozart\Criteria\Comparison\Matches;
use Webmozart\Criteria\Comparison\NotEmpty;
use Webmozart\Criteria\Comparison\NotEquals;
use Webmozart\Criteria\Comparison\NotNull;
use Webmozart\Criteria\Comparison\NotSame;
use Webmozart\Criteria\Comparison\Null;
use Webmozart\Criteria\Comparison\OneOf;
use Webmozart\Criteria\Comparison\Same;
use Webmozart\Criteria\Comparison\StartsWith;
use Webmozart\Criteria\Comparison\True;
use Webmozart\Criteria\Criteria;
use Webmozart\Criteria\Key\Key;
use Webmozart\Criteria\Key\KeyExists;
use Webmozart\Criteria\Key\KeyNotExists;

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
                return $this;
            }
        }

        $this->conjuncts[] = $x;

        return $this;
    }

    public function andNot(Criteria $criteria)
    {
        return $this->andX(new Not($criteria));
    }

    public function andNull($field)
    {
        return $this->andX(new Key($field, new Null()));
    }

    public function andNotNull($field)
    {
        return $this->andX(new Key($field, new NotNull()));
    }

    public function andEmpty($field)
    {
        return $this->andX(new Key($field, new IsEmpty()));
    }

    public function andNotEmpty($field)
    {
        return $this->andX(new Key($field, new NotEmpty()));
    }

    public function andTrue($field, $strict = true)
    {
        return $this->andX(new Key($field, new True($strict)));
    }

    public function andFalse($field, $strict = true)
    {
        return $this->andX(new Key($field, new False($strict)));
    }

    public function andEquals($field, $value)
    {
        return $this->andX(new Key($field, new Equals($value)));
    }

    public function andNotEquals($field, $value)
    {
        return $this->andX(new Key($field, new NotEquals($value)));
    }

    public function andSame($field, $value)
    {
        return $this->andX(new Key($field, new Same($value)));
    }

    public function andNotSame($field, $value)
    {
        return $this->andX(new Key($field, new NotSame($value)));
    }

    public function andGreaterThan($field, $value)
    {
        return $this->andX(new Key($field, new GreaterThan($value)));
    }

    public function andGreaterThanEqual($field, $value)
    {
        return $this->andX(new Key($field, new GreaterThanEqual($value)));
    }

    public function andLessThan($field, $value)
    {
        return $this->andX(new Key($field, new LessThan($value)));
    }

    public function andLessThanEqual($field, $value)
    {
        return $this->andX(new Key($field, new LessThanEqual($value)));
    }

    public function andOneOf($field, array $values, $strict = true)
    {
        return $this->andX(new Key($field, new OneOf($values, $strict)));
    }

    public function andMatches($field, $regExp)
    {
        return $this->andX(new Key($field, new Matches($regExp)));
    }

    public function andStartsWith($field, $prefix)
    {
        return $this->andX(new Key($field, new StartsWith($prefix)));
    }

    public function andEndsWith($field, $suffix)
    {
        return $this->andX(new Key($field, new EndsWith($suffix)));
    }

    public function andKey($field, $key, Criteria $criteria)
    {
        return $this->andX(new Key($field, new Key($key, $criteria)));
    }

    public function andKeyExists($field, $key)
    {
        return $this->andX(new Key($field, new KeyExists($key)));
    }

    public function andKeyNotExists($field, $key)
    {
        return $this->andX(new Key($field, new KeyNotExists($key)));
    }

    public function andKeyNull($field, $key)
    {
        return $this->andX(new Key($field, new Key($key, new Null())));
    }

    public function andKeyNotNull($field, $key)
    {
        return $this->andX(new Key($field, new Key($key, new NotNull())));
    }

    public function andKeyEmpty($field, $key)
    {
        return $this->andX(new Key($field, new Key($key, new IsEmpty())));
    }

    public function andKeyNotEmpty($field, $key)
    {
        return $this->andX(new Key($field, new Key($key, new NotEmpty())));
    }

    public function andKeyTrue($field, $key, $strict = true)
    {
        return $this->andX(new Key($field, new Key($key, new True($strict))));
    }

    public function andKeyFalse($field, $key, $strict = true)
    {
        return $this->andX(new Key($field, new Key($key, new False($strict))));
    }

    public function andKeyEquals($field, $key, $value)
    {
        return $this->andX(new Key($field, new Key($key, new Equals($value))));
    }

    public function andKeyNotEquals($field, $key, $value)
    {
        return $this->andX(new Key($field, new Key($key, new NotEquals($value))));
    }

    public function andKeySame($field, $key, $value)
    {
        return $this->andX(new Key($field, new Key($key, new Same($value))));
    }

    public function andKeyNotSame($field, $key, $value)
    {
        return $this->andX(new Key($field, new Key($key, new NotSame($value))));
    }

    public function andKeyGreaterThan($field, $key, $value)
    {
        return $this->andX(new Key($field, new Key($key, new GreaterThan($value))));
    }

    public function andKeyGreaterThanEqual($field, $key, $value)
    {
        return $this->andX(new Key($field, new Key($key, new GreaterThanEqual($value))));
    }

    public function andKeyLessThan($field, $key, $value)
    {
        return $this->andX(new Key($field, new Key($key, new LessThan($value))));
    }

    public function andKeyLessThanEqual($field, $key, $value)
    {
        return $this->andX(new Key($field, new Key($key, new LessThanEqual($value))));
    }

    public function andKeyOneOf($field, $key, array $values, $strict = true)
    {
        return $this->andX(new Key($field, new Key($key, new OneOf($values, $strict))));
    }

    public function andKeyMatches($field, $key, $regExp)
    {
        return $this->andX(new Key($field, new Key($key, new Matches($regExp))));
    }

    public function andKeyStartsWith($field, $key, $prefix)
    {
        return $this->andX(new Key($field, new Key($key, new StartsWith($prefix))));
    }

    public function andKeyEndsWith($field, $key, $suffix)
    {
        return $this->andX(new Key($field, new Key($key, new EndsWith($suffix))));
    }

    /**
     * {@inheritdoc}
     */
    public function match($values)
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
