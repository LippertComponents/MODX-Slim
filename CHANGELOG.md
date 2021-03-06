# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [0.3.4] - 2019-05-23
### Changed
- Do not allow an empty limit if in PaginationHelper when $this->allow_no_limit = false.  Set a default limit if limit is set to 0

## [0.3.3] - 2019-05-23
### Changed
- Fixed division by zero in the PaginationHelper when $this->allow_no_limit = true and limit is set to 0

## [0.3.2] - 2019-05-23
### Changed
- Allow the PaginationHelper to return all if class sets $this->allow_no_limit = true and limit is set to 0

## [0.3.0] - 2019-03-18

### Changed
- Fix #1 the location of the package configuration has changed from 
`core/vendor/lci/modx-slim/src/cache/package.php` to `core/config/lci_modx_slim_package.php`. 
Manually copy before running composer update.

## [0.2.1] - 2019-03-18
### Fixed

- Committed the src/database/migrations directory to allow Orchestrator to auto install the lci/modx-slim package

## [0.2.0] - 2019-03-18
### Added

- New PaginationHelper Trait, can use this trait in any class that is an xPDO object to paginate data

## [0.1.0] - 2018-11-28

Initial release 

[Unreleased]: https://github.com/LippertComponents/MODX-Slim/compare/v0.3.0...HEAD
[0.3.2]: https://github.com/LippertComponents/MODX-Slim/compare/v0.3.0...v0.3.2
[0.3.0]: https://github.com/LippertComponents/MODX-Slim/compare/v0.2.0...v0.3.0
[0.2.1]: https://github.com/LippertComponents/MODX-Slim/compare/v0.2.0...v0.2.1
[0.2.0]: https://github.com/LippertComponents/MODX-Slim/compare/v0.1.0...v0.2.0
[0.1.0]: https://github.com/LippertComponents/MODX-Slim/releases/tag/v0.1.0

