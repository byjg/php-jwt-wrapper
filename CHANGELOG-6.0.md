# Changelog - Version 6.0

## Overview

Version 6.0 is a major release that modernizes the JWT Wrapper library with updated PHP requirements, improved code quality, and comprehensive documentation. This release focuses on maintaining compatibility with modern PHP versions while enhancing developer experience.

## New Features

### PHP 8.4 and 8.5 Support
- Added support for PHP 8.4
- Added support for PHP 8.5
- Updated GitHub Actions workflow to test against PHP 8.3, 8.4, and 8.5

### Enhanced Documentation
- Complete documentation restructure with dedicated markdown files:
  - `docs/overview.md` - Introduction and core concepts
  - `docs/key-types.md` - HMAC and OpenSSL key configuration
  - `docs/creating-tokens.md` - Token generation and customization
  - `docs/validating-tokens.md` - Token validation and data extraction
  - `docs/api-reference.md` - Complete class and method documentation
- Updated README with improved examples and better structure
- Added documentation links for easier navigation

### Code Quality Improvements
- Added PHP 8.3+ `#[Override]` attributes to interface implementations
- Added typed constants for JWT standard claims (`IssuedAt`, `JsonTokenId`, `Issuer`, `NotBefore`, `Expire`, `Subject`)
- Improved type hints in `JwtOpenSSLKey` constructor parameters
- Fixed spacing in `JwtAlgorithmTrait` array property declaration
- Refined `getAuthorizationBearer()` method logic for better null safety

### Development Environment
- Added GitPod configuration (`.gitpod.yml`)
- Added VS Code launch configuration (`.vscode/launch.json`)
- Added PhpStorm run configuration (`.run/psalm.run.xml`)
- Added composer scripts for common tasks:
  - `composer test` - Run PHPUnit tests
  - `composer psalm` - Run Psalm static analysis

### Dependency Updates
- Updated PHPUnit to `^10.5|^11.5` (from `^9.6`)
- Updated Psalm to `^5.9|^6.13` (from `^5.9`)
- Updated Firebase JWT to remain at `^6`
- Added `prefer-stable` and `minimum-stability: dev` to composer.json

## Bug Fixes

- Fixed `getAuthorizationBearer()` method to properly handle missing authorization headers
- Fixed Psalm configuration for better static analysis
- Fixed spacing issues in source code

## Breaking Changes

| Before | After | Description |
|--------|-------|-------------|
| PHP 8.1 - 8.3 | PHP 8.3 - 8.5 | **Minimum PHP version increased from 8.1 to 8.3**. Projects using PHP 8.1 or 8.2 must upgrade to PHP 8.3 or higher. |
| Untyped constants | Typed constants | Constants (`IssuedAt`, `JsonTokenId`, `Issuer`, `NotBefore`, `Expire`, `Subject`) now have explicit `string` type declarations. This may affect runtime behavior in edge cases with strict typing. |
| `sscanf()` direct assignment | Array access | The `getAuthorizationBearer()` method now uses array access for `sscanf()` results instead of list assignment. This improves null safety but may affect code relying on the previous implementation details. |

## Path to Upgrade from 5.x to 6.0

### Step 1: Check PHP Version
Ensure your environment is running PHP 8.3 or higher:
```bash
php -v
```

If you're on PHP 8.1 or 8.2, upgrade to PHP 8.3, 8.4, or 8.5 before proceeding.

### Step 2: Update Composer Dependencies
Update your `composer.json` to require version 6.0:
```bash
composer require byjg/jwt-wrapper:^6.0
```

### Step 3: Run Tests
After updating, run your test suite to ensure compatibility:
```bash
vendor/bin/phpunit
```

### Step 4: Review Code for Breaking Changes

#### PHP Version Check
If you have any PHP version checks in your code, update them:
```php
// Before
if (PHP_VERSION_ID < 80100) {
    throw new Exception('Requires PHP 8.1+');
}

// After
if (PHP_VERSION_ID < 80300) {
    throw new Exception('Requires PHP 8.3+');
}
```

#### Constant Usage (Edge Cases Only)
If you're using the JWT constants in unusual ways (e.g., type checking), be aware they now have explicit string types:
```php
// This should work the same, but now with stronger typing
$claim = JwtWrapper::IssuedAt; // string type is now explicit
```

#### Authorization Bearer Extraction
If you've extended or overridden the `getAuthorizationBearer()` method, review the updated implementation:
```php
// New implementation uses safer array access
$result = sscanf($authorization, 'Bearer %s');
$bearer = $result[0] ?? "";
```

### Step 5: Update Development Dependencies (Optional)
If you're developing with this library, consider updating your dev tools:
```bash
composer update --dev
```

This will pull in PHPUnit 10.5/11.5 and Psalm 5.9/6.13.

### Step 6: Review New Documentation
Familiarize yourself with the new documentation structure:
- Read `docs/overview.md` for core concepts
- Review `docs/api-reference.md` for detailed API documentation
- Check examples in the `example` directory

### Common Migration Issues

**Issue**: PHP version too old
**Solution**: Upgrade to PHP 8.3 or higher

**Issue**: PHPUnit tests fail after upgrade
**Solution**: Update your PHPUnit configuration to version 10.5 or 11.5 compatible format

**Issue**: Psalm errors in your code
**Solution**: Run `vendor/bin/psalm` to identify and fix type-related issues

### Rollback Plan
If you encounter issues and need to rollback:
```bash
composer require byjg/jwt-wrapper:^5.0
```

## Notes

- This release maintains backward compatibility for most use cases
- The primary breaking change is the PHP version requirement
- No changes to the public API or method signatures beyond type improvements
- All existing functionality remains intact
