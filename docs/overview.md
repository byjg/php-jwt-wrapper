---
sidebar_position: 1
---

# PHP JWT Wrapper

A simple and straightforward wrapper for the Firebase JWT library. This library makes it easy to create, encode, and decode JWT tokens in PHP applications.

## Purpose

This library provides an abstraction layer over the Firebase JWT library with these key benefits:

- Simplified API for creating and validating JWT tokens
- Support for both HMAC and RSA signing methods
- Easy integration with PHP applications
- Automatic handling of common JWT fields (issued at, expires, etc.)
- Intuitive interface for extracting data from tokens

## Requirements

- PHP 8.1 or higher
- Firebase JWT library
- OpenSSL extension

## Installation

The library can be installed via Composer:

```bash
composer require "byjg/jwt-wrapper"
```

## Basic Workflow

The library is designed to be used on the server side for:

1. **Token Creation**: Generate JWT tokens to send to clients
2. **Token Validation**: Validate tokens received from clients
3. **Data Extraction**: Extract data from validated tokens

See the subsequent documentation sections for detailed usage examples.

## See Also

- [Key Types](key-types.md) - Learn about the different types of keys supported by the library
- [Creating Tokens](creating-tokens.md) - Step-by-step guide to creating JWT tokens 