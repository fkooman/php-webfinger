# Introduction
Simple WebFinger server.

# Installation

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
    Server: Apache/2.4.16 (Fedora) OpenSSL/1.0.1k-fips PHP/5.6.14 mod_wsgi/4.4.8 Python/2.7.10
    X-Powered-By: PHP/5.6.14
    Access-Control-Allow-Origin: *
    Content-Length: 43
    Content-Type: application/jrd+json

    {
        "links": [],
        "subject": "acct:foo@localhost"
    }

This is without any configuration snippets. For example, with the snippet for
remoteStorage:

    $ curl -k -i https://localhost/.well-known/webfinger?resource=acct:foo@localhost
    HTTP/1.1 200 OK
    Date: Tue, 20 Oct 2015 14:13:29 GMT
    Server: Apache/2.4.16 (Fedora) OpenSSL/1.0.1k-fips PHP/5.6.14 mod_wsgi/4.4.8 Python/2.7.10
    X-Powered-By: PHP/5.6.14
    Access-Control-Allow-Origin: *
    Content-Length: 531
    Content-Type: application/jrd+json

    {
        "links": [
            {
                "href": "https://localhost/php-remote-storage/foo",
                "properties": {
                    "http://remotestorage.io/spec/version": "draft-dejong-remotestorage-05",
                    "http://remotestorage.io/spec/web-authoring": null,
                    "http://tools.ietf.org/html/rfc6749#section-4.2": "https://localhost/php-remote-storage/authorize?login_hint=foo",
                    "http://tools.ietf.org/html/rfc6750#section-2.3": null,
                    "http://tools.ietf.org/html/rfc7233": null
                },
                "rel": "http://tools.ietf.org/id/draft-dejong-remotestorage"
            }
        ],
        "subject": "acct:foo@localhost"
    }

