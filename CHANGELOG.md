# Change Log

All notable changes to this project will be documented in this file.
See [Conventional Commits](https://conventionalcommits.org) for commit guidelines.

## 1.0.1

- fix: bug access pricing data via the trait method
- fix: CastPricing array decode
- fix: Pricing data to remain null by default when not set

## 1.1.0

- feat: switch to [Pest](https://pestphp.com/docs/plugins/laravel) framework for tests
- feat: quick methods for pricing model check; `isStandard`, `isPackage`, `isVolume` and `isGraduated`
- fix: CastPricing to cast attributes per single Pricing instance
- feat: CastMultiPricing to cast multiple Pricing instances
- feat: add pricing summaries

## 1.1.1

- feat: make Pricing array accessible

## 1.1.2

- fix: CastPricing setter to accept Pricing object

## 1.1.3

- fix: make `Pricing.$unit_amount` and `Pricing.$units` nullable.

## 1.2.2

- feat: add functions to basic arithmetics: `add`, `subtract`, `divide`, `multiply`.
- feat: add precision option in `price` method

## 1.2.3

- fix: `CastPricing` caster should return `null` for empty pricing attributes

## 1.2.4

- fix: `Pricing` should have a default value of `0` for `unit_amount`
- fix: `CastPricing` caster should only encode valid value
- chore: test setting volume pricing using array

## 1.2.5

- feat: Laravel 10 support
