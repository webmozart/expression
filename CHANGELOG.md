Changelog
=========

* 1.0.0-beta3 (2015-05-28)

 * optimized `Valid::andX()` and `Valid::orX()`
 * optimized `Invalid::andX()` and `Invalid::orX()`
 * removed `true()` and `false()`. Use `same()` instead
 * renamed `valid()` to `true()` and `invalid()` to `false()`
 * added `contains()`
 * added brackets around string output of nested conjunctions/disjunctions

* 1.0.0-beta2 (2015-04-13)

 * added `Selector`
 * removed `key*()` methods
 * renamed argument `$field` to `$key`, moved it to end of the method arguments
   and made it optional for all test methods
 * removed argument `$field` from `key()`
 * removed argument `$strict` from `true()`, `false()` and `oneOf()`
 * added `atLeast()` selector
 * added `exactly()` selector
 * added `all()` selector
 * added `atMost()` selector
 * added `count()` selector
 * renamed `oneOf()` to `in()`
 * added `valid()`
 * added `invalid()`
 * optimized `andValid()`, `andInvalid()`, `orValid()` and `orInvalid()`

* 1.0.0-beta (2015-03-19)

 * first beta release
