Changelog
=========

* 1.0.0 (2015-12-17)

 * added `Expr::filter()` to filter collections
 * removed `final` keyword of expression classes to facilitate building
   domain expressions
 * renamed `Conjunction` to `AndX`
 * renamed `Disjunction` to `OrX`
 * added `Expr::andX()` and `Expr::orX()`

* 1.0.0-beta5 (2015-10-02)

 * added `method()` selector
 * added `property()` selector
 * removed optional `$key` arguments. Use the `key()`/`method()`/`property()`
   selectors instead
 * added `isInstanceOf()`
 * removed class `NotEmpty` and used `Not` with `IsEmpty` instead
 * renamed `Webmozart\Expression\Comparison` namespace to `Webmozart\Expression\Constraint`
 * fixed type juggling in `equivalentTo()`

* 1.0.0-beta4 (2015-08-24)

 * fixed return types in `Expr::true()` and `Expr::false()`
 * fixed minimum versions in composer.json

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
