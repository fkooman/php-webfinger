# Introduction
Simple WebFinger server.

# Installation

# Configuration
You can place WebFinger configuration snippets in `config/conf.d`. This will 
add the snippet to the `links` section of the WebFinger data. 

There are two variables that will be expanded: `__USER__` and `__DOMAIN__`. 
For example:

    {
        "href": "https://__DOMAIN__/php-remote-storage/__USER__",
        "properties": {
            "http://remotestorage.io/spec/version": "draft-dejong-remotestorage-03",
            "http://tools.ietf.org/html/rfc2616#section-14.16": "false",
            "http://tools.ietf.org/html/rfc6749#section-4.2": "https://__DOMAIN__/php-remote-storage/authorize",
            "http://tools.ietf.org/html/rfc6750#section-2.3": "false"
        },
        "rel": "remotestorage"
    }

All files in `config/conf.d` that end with the `.conf` extension are included.

## Apache

    Alias /.well-known/webfinger /var/www/php-webfinger/web/webFinger.php

    <Directory /var/www/php-webfinger/web>
        AllowOverride None

        Require local
        #Require all granted
    </Directory>

# Query
For example:

    $ curl -k https://storage.example/.well-known/webfinger?resource=acct:foo@storage.example | python -mjson.tool
    {
        "links": [
            {
                "href": "https://storage.example/php-remote-storage/foo",
                "properties": {
                    "http://remotestorage.io/spec/version": "draft-dejong-remotestorage-03",
                    "http://tools.ietf.org/html/rfc2616#section-14.16": "false",
                    "http://tools.ietf.org/html/rfc6749#section-4.2": "https://storage.example/php-remote-storage/authorize",
                    "http://tools.ietf.org/html/rfc6750#section-2.3": "false"
                },
                "rel": "remotestorage"
            }
        ],
        "subject": "acct:foo@storage.example"
    }

Or without any configuration snippets:

    {
        "links": [],
        "subject": "acct:foo@storage.example"
    }

