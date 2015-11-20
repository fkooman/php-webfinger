# Introduction
Simple WebFinger server.

# Configuration
You can place WebFinger configuration snippets in `config/conf.d`. This will 
add the snippet to the `links` section of the WebFinger data. 

There are two variables that will be expanded: `__USER__`, to the user part of
the resource query, and `__HOST__` to the `Host` header value of the HTTP
request.

All files in `config/conf.d` that end with the `.conf` extension are included
in the WebFinger output.

## Apache

    Alias /.well-known/webfinger /var/www/php-webfinger/web/index.php

    <Directory /var/www/php-webfinger/web>
        AllowOverride None

        Require local
        #Require all granted
    </Directory>

# Query
For example:

    $ curl -k -i https://localhost/.well-known/webfinger?resource=acct:foo@localhost
    HTTP/1.1 200 OK
    Date: Tue, 20 Oct 2015 13:56:50 GMT
    Server: Apache
    Access-Control-Allow-Origin: *
    Content-Length: 43
    Content-Type: application/jrd+json

    {
        "links": [],
        "subject": "acct:foo@localhost"
    }
