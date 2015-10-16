# Introduction
Simple WebFinger server.

# Installation

# Configuration

## Apache

    Alias /.well-known/webfinger /var/www/php-webfinger/web/webFinger.php

    <Directory /var/www/php-webfinger/web>
        AllowOverride None

        Require local
        #Require all granted
    </Directory>
