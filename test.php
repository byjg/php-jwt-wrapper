<?php

//https://github.com/travist/jsencrypt

$publicKey = "-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC+ALczN1La2w7FxXSjccRQ/nTC
7O3IqjPTiaZPlQTpfg+dOi1BnJy5aw73/qg9RMit+tuJ1M9kvTcxqn87ObinkabF
xiMuEk8m57Subc4ePt3bGQfKAHz/2TUA5u8pIXPsEuHKbKdocc0W71VpCCBdNqiN
N9LXqRFOdTTUudNDIQIDAQAB
-----END PUBLIC KEY-----";

$privateKey = "-----BEGIN RSA PRIVATE KEY-----
MIICXQIBAAKBgQC+ALczN1La2w7FxXSjccRQ/nTC7O3IqjPTiaZPlQTpfg+dOi1B
nJy5aw73/qg9RMit+tuJ1M9kvTcxqn87ObinkabFxiMuEk8m57Subc4ePt3bGQfK
AHz/2TUA5u8pIXPsEuHKbKdocc0W71VpCCBdNqiNN9LXqRFOdTTUudNDIQIDAQAB
AoGAC3OjlxSoi8RUOZtTEl7TBEax5uW81zFa+k77lSRYLXKJomJVQ/UahRpsxom0
viydI89Q2BfZgCrfXsD19i3ecTB/XABlk2aLrr0ak/V/InIKwvIteONqcZrg/Ty3
Gcb/goMGWFsOqLKAMSV8hM6/b0VNBAcvCOPdbkkgIawdrTkCQQDjD3kqVh+CzM6b
TJFMsr0mWX13mA9KMJFOS6hc7ULQACuAWQ34ZGCd1rBM1mIx9/N0/Z5joHQHNKZH
KcIpXOjfAkEA1jggKibEaPEWRVJl+Jg1FAggITcEJjRBm7k/zP/LOJx8GSdudiPw
CEV9/LvMr0GO6OyQc9Hyal0Z0h2OdxlT/wJAZUjXsaztLXmSh+/luKLagSrWLuzj
lSKJDrXtClbDwOjyfrQ66RxNhNrplbzj3IpQTVV8u9AtMbGoooHcHHtXXQJBAKQC
VJ3xEG9IJcTtUSUDY5/ymKbVeFfHqnOPYUmSjgTJyjl39xp8aUnr6omVPyDvEHtE
o1QJaZAFt78m4exNeAECQQCWCQWM/FZ6STTDQzu6gcgW0fjaDMMFGczy5wv1DVBh
ATiRd8uF66U9afoPbnkhko4VBBLqX2OKp/giCs6CEpU1
-----END RSA PRIVATE KEY-----";

$crypted = "";
openssl_public_encrypt ( 'Hello World', $crypted , $publicKey);

echo base64_encode($crypted) . "\n\n";

$decrypted = "";
openssl_private_decrypt($crypted, $decrypted, $privateKey);

echo $decrypted . "\n\n";
