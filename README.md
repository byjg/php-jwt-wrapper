# Wrapper for Firebase/Jwt

A very simple wrapper for create, encode, decode JWT Tokens and abstract the PHP JWT Component


## How it works

This library is intented to be located at server side. 

The flow is

Without Token:

```
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

```
                        Return the 
       Pass Token       API Result
CLIENT ----------> API ----------->CLIENT
  |                 |                 
Wants to       Validate and         
Access         Extract Token        
Private      (JwtWrapper::extractData)
Resource
```

## Running a sample test

Start a local server:

```
php -S localhost:8080
```

Access from you web browser the client.html

```
http://localhost:8080/client.html
```

