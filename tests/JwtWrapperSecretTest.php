<?php

namespace Test;

use ByJG\JwtWrapper\JwtWrapper;
use Override;

require_once __DIR__ . '/JwtWrapperHashTest.php';

class JwtWrapperSecretTest extends JwtWrapperHashTest
{
    #[Override]
    protected function setUp(): void
    {
        $private = <<<TEXT
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

        $this->jwtKey = new \ByJG\JwtWrapper\JwtOpenSSLKey($private, $public);

        unset($_SERVER["HTTP_AUTHORIZATION"]);
        $this->object = new JwtWrapper($this->server, $this->jwtKey);
    }


    #[Override]
    public function testTokenWrongSecret()
    {
        $this->expectException(\Firebase\JWT\SignatureInvalidException::class);

        $jwt = $this->object->createJwtData($this->dataToToken);
        $token = $this->object->generateToken($jwt);

        $private = <<<TEXT
-----BEGIN RSA PRIVATE KEY-----
MIIEpgIBAAKCAQEA9QTmRYW+S+9QeylWIz3cAMAjaIsJeO32/2IKYS54BBgd9xYp
ByUUabiua8YvKwv5lmWv2P/llzUQz5ppU1nkiZljeofkEmxdxKTaLhX5Cd4WZteC
Ef3SfAY3XMoCeNfXFqKt53SAULH2Ao0HzS+tXdru+dES1uD3jcIDMcD+vbQPhDos
XM96b95Wmi9O5OBbNmZQsBcqv4Ixd8KXokWuNm3Pyo1oa6nCEk08H2wx/26zQ/Jf
3TsVJ+jYd0UgKsfQxtMU2tMIvJV8bzjS++HHhd/O/oW8go0AAOuSygoPWE0y88dA
H3bHiWlpRupT8ENCYeMpObpTRXsAFwUn49iofQIDAQABAoIBAQC7vFhP9q0bc6+f
3slgUVqLvKykwrusS/EQNuerFLbitDPpibJABjpA0z/Z1k1310IS6bE2PMSG+iL6
Xt7K/bqtb7kYPp0TPLMQJBwEadOaqu9RN1kzPd+UJhMvZp1ESGVGs7k5+jsDGYhc
5gCPSDO3ETRAd+DOgitME9bsvWqyoI/frI/pFy7Ts4CxoyPgXE1uSZB0D7yPRa3u
IQ/VX0tBCPx84+81wMdaYGyhVKfa+K/rK9bMvt8xYz/OALRxHLoM157Rmi2HpB90
2H9N4OnW8NdzdQvAT2PAFYicjEbTszNQ3y8e/WQH+OvpfPW1UpH842D4z2swAfbN
CU+MLeVlAoGBAPxg6fnKxQpa1oNUgka+MdkJSLTxDUA8YA2Si8hbaVm8TluZJR4x
CR5c4ElQauSM1X72pqUAdu29wzW67Cy/mXhlCBHm03rnZVVVsmcf/yRLLTHhstmM
qaXvh08zSb11BUPLp9Alfrsdduzwz4CiKfiaRvFBdphvctzmlVZFszm/AoGBAPiI
85FMtNi7ImzPbpKaJvjYppW6MdDIPd9rQLh0oWxxAZtodZm8MfmXEqAzWq0Nxq4C
UyCcp4s6hL1GaTi+R2LcWj0ZxRqYwykRVnptZL9CXzek3lngX5yxOvIV4fHkIIvf
DKviEQi6kzqLJqRKBiSxp/HmnO+ekF07F1lQ31TDAoGBANxBDBFW+A1oZ2uoFiX9
GO3L32siMClOR5mwulM6C8Anyc7A4ZbuvoGEFq2FBDTAABbU1WyM8j3bbYD3x+Pp
tttOePN+mjPZvCL0LkU3tGiNPz0YNwkLbIcHevQQ05sHhHe9RZAvGOHd/gscwksF
u1Fd+unT8tdn9Dt69PucyqRrAoGBAJfCeIgWwK8+xayPlUMrofR9xmrTASwtuao9
QY3gmdkvv/13cafoRNPVLehos0vMh68leEEHpz7bAsbYwdOGTOzPBMMegz7UXQcs
sROczNIE40OFBsj2uythBU9hkVA1LrJ6BrDGIASmeNRct8HF+a5aVOTfHqEqv5hO
RtmhCl4lAoGBANqI7xQvrP/OWJIZlHahnQbcrcBgsD6IM3kIKdR4s3R08ce20so/
M1wHFjLKjlHJ6GqeP1y1RV7Ej4kK9vrEz5OYoJlQnjhU2LdREfVdhzuy9tgiW1EW
mSSFNrpqXwau5wBYZsR7aukXroAMp9ZyUhDjvMP0e4Nh+dajyBBQv5WE
-----END RSA PRIVATE KEY-----
TEXT;
        $public = <<<TEXT
-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA9QTmRYW+S+9QeylWIz3c
AMAjaIsJeO32/2IKYS54BBgd9xYpByUUabiua8YvKwv5lmWv2P/llzUQz5ppU1nk
iZljeofkEmxdxKTaLhX5Cd4WZteCEf3SfAY3XMoCeNfXFqKt53SAULH2Ao0HzS+t
Xdru+dES1uD3jcIDMcD+vbQPhDosXM96b95Wmi9O5OBbNmZQsBcqv4Ixd8KXokWu
Nm3Pyo1oa6nCEk08H2wx/26zQ/Jf3TsVJ+jYd0UgKsfQxtMU2tMIvJV8bzjS++HH
hd/O/oW8go0AAOuSygoPWE0y88dAH3bHiWlpRupT8ENCYeMpObpTRXsAFwUn49io
fQIDAQAB
-----END PUBLIC KEY-----
TEXT;

        $jwtWrapper = new JwtWrapper($this->server, \ByJG\JwtWrapper\JwtOpenSSLKey::getInstance($private, $public));

        $jwtWrapper->extractData($token);
    }
}
