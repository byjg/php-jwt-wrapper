# Jwt-Wrapper for Firebase Jwt

[![Build Status](https://github.com/byjg/jwt-wrapper/actions/workflows/phpunit.yml/badge.svg?branch=master)](https://github.com/byjg/jwt-wrapper/actions/workflows/phpunit.yml)
[![Opensource ByJG](https://img.shields.io/badge/opensource-byjg-success.svg)](http://opensource.byjg.com)
[![GitHub source](https://img.shields.io/badge/Github-source-informational?logo=github)](https://github.com/byjg/jwt-wrapper/)
[![GitHub license](https://img.shields.io/github/license/byjg/jwt-wrapper.svg)](https://opensource.byjg.com/opensource/licensing.html)
[![GitHub release](https://img.shields.io/github/release/byjg/jwt-wrapper.svg)](https://github.com/byjg/jwt-wrapper/releases/)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/byjg/jwt-wrapper/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/byjg/jwt-wrapper/?branch=master)

A very simple wrapper for create, encode, decode JWT Tokens and abstract the PHP JWT Component

## How it works

This library is intented to be located at server side.

The flow is

Without Token:

```text
         Request         Return 
         Token           Token
CLIENT ---------->LOGIN----------->CLIENT
  |                 |                 |
Without          Generate           Store
Token            Token              Locally
           (JwtWrapper::createJwtData)
           (JwtWrapper::generateToken)
```

With token

```text
                        Return the 
       Pass Token       API Result
CLIENT ----------> API ----------->CLIENT
  |                 |                 
Wants to       Validate and         
Access         Extract Token        
Private      (JwtWrapper::extractData)
Resource
```

## Create your Jwt Secret Key

You can use two type of secret keys. A Hash (HS512) that is faster, or a RSA (RS512) that is more secure.

### Hash Key

```bash
openssl rand -base64 64     # set here the size of your key
```

### RSA

```bash
ssh-keygen -t rsa -C "Jwt RSA Key" -b 2048 -f private.pem
openssl rsa -in private.pem -outform PEM -pubout -out public.pem
```

**Note**: Save without password

## Create JWT Token (Hash Encoding)

```php
<?php
$server = "example.com";
$secret = new \ByJG\Util\JwtKeySecret(base64_encode("secrect_key_for_test"));

$jwtWrapper = new \ByJG\Util\JwtWrapper($server, $secret);

$token = $jwtWrapper->createJwtData([
    "key" => "value",
    "key2" => "value2"
]);
```

## Create JWT Token (RSA Encoding)

```php
<?php
$server = "example.com";
$secret = <<<TEXT
-----BEGIN RSA PRIVATE KEY-----
MIIEpQIBAAKCAQEA5PMdWRa+rUJmg6QMNAPIXa+BJVN7W0vxPN3WTK/OIv5gxgmj
2inHGGc6f90TW/to948LnqGtcD3CD9KsI55MubafwBYjcds1o9opZ0vYwwdIV80c
OVZX1IUZFTbnyyKcXeFmKt49A52haCiy4iNxcRK38tOCApjZySx/NzMDeaXuWe+1
nd3pbgYa/I8MkECa5EyabhZJPJo9fGoSZIklNnyq4TfAUSwl+KN/zjj3CXad1oDT
7XDDgMJDUu/Vxs7h3CQI9zILSYcL9zwttbLnJW1WcLlAAIaAfABtSZboznsStMnY
to01wVknXKyERFs7FLHYqKQANIvRhFTptsehowIDAQABAoIBAEkJkaQ5EE0fcKqw
K8BwMHxKn81zi1e9q1C6iEHgl8csFV03+BCB4WTUkaH2udVPJ9ZJyPArLbQvz3fS
wl1+g4V/UAksRtRslPkXgLvWQ2k8KoTwBv/3nn9Kkozk/h8chHuii0BDs30yzSn4
SdDAc9EZopsRhFklv9xgmJjYalRk02OLck73G+d6MpDqX56o2UA/lf6i9MV19KWP
HYip7CAN+i6k8gA0KPHwr76ehgQ6YHtSntkWS8RfVI8fLUB1UlT3HmLgUBNXMWkQ
ZZbvXtNOt6NtW/WIAHEYeE9jmFgrpW5jKJSLn5iGVPFZwJIZXRPyELEs9NHWkS6e
GmdzxnECgYEA8+m05B/tmeZOuMrPVJV9g+aBDcuxmW+sdLRch+ccSmx4ZNQOLVoU
klYgTZq/a1O4ENq0h2WgccNlRHdcH4sXMBvLalA/tFhZMUuA/KXWyZ1F0hBnjHVF
cj1alHCqh+9qJDGdn4mxSmrp8p0rfeWgBwlFtJEJmjjDWDCtVY+JZcsCgYEA8EuV
WF/ilgDjgC4jMCYNuO0oFGBbtNP17PuU3kh8W+joqK/nufZ3NLy1WrDIpqa9YPex
328Nnjljf5GJWSdMchAp82waLzl7FaaBTY0iyFAK4J0jfC/fVLx82+wpM3utDnh8
9x5iIboO5U7uEJ7k8X2p64GoprlKJSRmGAJ7eIkCgYEAw5IsXI3NMY0cqcbUHvoO
PehgqfMdX+3O1XSYjM+eO35lulLdWzfTLtKn7BGcUi46dCkofzfZQd5uIEukLhaU
bRqcK45UxgHg4kmsDufaJKZaCWjl3hVZrZPMQSFlWsF41bSCshzxbr3y/3lOGhA4
E+w3W+S/Uk0ZNGkzUltYy6kCgYEA0gRNeBr9z7rhG4O3j3qC3dCxCfYZ0Na8hy5v
M0PJJQ9QYTa04iyOjVItcyE1jaoHtLtoA+9syJBB7RoHIBufzcVg1Pbzf7jOYeLP
+jbTYp3Kk/vjKsQwfj/rJM+oRu3eF9qo5dbxT6btI++zVGV7lbEOFN6Sx30EV6gT
bwKkZXkCgYEAnEtN43xL8bRFybMc1ZJErjc0VocnoQxCHm7LuAtLOEUw6CwwFj9Q
GOl+GViVuDHUNQvURLn+6gg4tAemYlob912xIPaU44+lZzTMHBOJBGMJKi8WogKi
V5+cz9l31uuAgNfjL63jZPaAzKs8Zx6R3O5RuezympwijCIGWILbO2Q=
-----END RSA PRIVATE KEY-----
TEXT;
          
$public = <<<TEXT
-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA5PMdWRa+rUJmg6QMNAPI
Xa+BJVN7W0vxPN3WTK/OIv5gxgmj2inHGGc6f90TW/to948LnqGtcD3CD9KsI55M
ubafwBYjcds1o9opZ0vYwwdIV80cOVZX1IUZFTbnyyKcXeFmKt49A52haCiy4iNx
cRK38tOCApjZySx/NzMDeaXuWe+1nd3pbgYa/I8MkECa5EyabhZJPJo9fGoSZIkl
Nnyq4TfAUSwl+KN/zjj3CXad1oDT7XDDgMJDUu/Vxs7h3CQI9zILSYcL9zwttbLn
JW1WcLlAAIaAfABtSZboznsStMnYto01wVknXKyERFs7FLHYqKQANIvRhFTptseh
owIDAQAB
-----END PUBLIC KEY-----
TEXT;

# Note that if you want to use RSA just pass the 3rd argument (public key)
# See above how to create the RSA Key pair.
$jwtKey = new \ByJG\Util\JwtRsaKey($secret, $public);
$jwtWrapper = new \ByJG\Util\JwtWrapper($server, $jwtKey);

$token = $jwtWrapper->createJwtData([
    "key" => "value",
    "key2" => "value2"
]);
```

## Extracting

```php
<?php
# If exists $_SERVER['HTTP_AUTHENTICATION'] = "Bearer $TOKEN"
$data = $jwtWrapper->extractData();

# If you want decode directly:
$data = $jwtWrapper->extractData($token);
```

### Adding a Leeway

You can add a leeway to account for when there is a clock skew times between
the signing and verifying servers. It is recommended that this leeway should
not be bigger than a few minutes.

```php
$jwtWrapper->setLeeway(60)
```

Important: Since the Firebase JWT class set the leeway value as a "static" property
once you call the method above it will set up the same value to all JwtWrapper instances

## Install

```bash
composer require "byjg/jwt-wrapper=5.0.*"
```

## Running the tests

```bash
vendor/bin/phpunit
```

## Running a sample test

Start a local server:

```bash
php -S localhost:8080
```

Access from you web browser the client.html

```bash
http://localhost:8080/client.html
```

## Dependencies

```mermaid  
flowchart TD  
    byjg/jwt-wrapper --> firebase/php-jwt
    byjg/jwt-wrapper --> ext-openssl
```

----  
[Open source ByJG](http://opensource.byjg.com)

