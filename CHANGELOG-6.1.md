# Changelog - Version 6.1

## Overview

Version 6.1 is a security-focused release that upgrades the `firebase/php-jwt` dependency from version 6 to version 7, eliminating a known security advisory (PKSA-y2cr-5h3j-g3ys) affecting all v6 releases.

## Changes

### Security

- Upgraded `firebase/php-jwt` from `^6` to `^7` to address security advisory PKSA-y2cr-5h3j-g3ys affecting all v6 releases

### Bug Fixes

- Updated test keys to meet the minimum key length requirement enforced by `firebase/php-jwt` v7 for HMAC algorithms

## Breaking Changes

| Before | After | Description |
|--------|-------|-------------|
| `firebase/php-jwt ^6` | `firebase/php-jwt ^7` | **HMAC keys must now meet minimum length requirements.** HS256 requires ≥ 32 bytes, HS384 requires ≥ 48 bytes, HS512 requires ≥ 64 bytes. Applications using short HMAC secrets must update them. |

## Path to Upgrade from 6.0 to 6.1

### Step 1: Update Composer Dependencies

```bash
composer require byjg/jwt-wrapper:^6.1
```

### Step 2: Verify HMAC Key Lengths

If you use `JwtHashHmacSecret`, ensure your key meets the minimum length for your chosen algorithm:

| Algorithm | Minimum key length |
|-----------|--------------------|
| HS256     | 32 bytes           |
| HS384     | 48 bytes           |
| HS512     | 64 bytes           |

Example of a compliant key for HS512:
```php
// Key must be at least 64 characters when decode=false
$jwtKey = JwtHashHmacSecret::getInstance('your-very-long-secret-key-at-least-64-bytes-long-for-hs512!!', false);
```

### Step 3: Run Tests

```bash
vendor/bin/phpunit
```

### Rollback Plan

If you need to rollback:
```bash
composer require byjg/jwt-wrapper:^6.0
```