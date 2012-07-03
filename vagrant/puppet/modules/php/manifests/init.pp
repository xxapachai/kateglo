class php {
    package {
        "php5" :
            ensure => installed,
            require => Exec["apt-get update"]
    }

    package {
        ["php5-cli", "php-pear", "php5-suhosin", "php5-xdebug", "php-apc",
        "php5-curl", "php5-gd", "php5-mysql", "php5-xsl", "libapache2-mod-php5",
        "apache2-mpm-prefork"] :
            ensure => installed,
            require => Package["php5"]
    }

    # upgrade PEAR
    exec { "pear upgrade":
        require => Package["php-pear"]
    }

    # set auto discover
    exec { "pear config-set auto_discover 1":
        require => Exec["pear upgrade"]
    }

    exec { "pear clear-cache":
        require => Exec["pear config-set auto_discover 1"],
        onlyif => 'test "pear config-get auto_discover" = "0"'
    }

    exec { "pear install pear.phpunit.de/PHP_CodeCoverage":
            require => Exec["pear clear-cache"],
            creates => "/usr/share/php/PHP/CodeCoverage"
    }

    exec { "pear install pear.phpunit.de/PHPUnit":
            require => Exec["pear install pear.phpunit.de/PHP_CodeCoverage"],
            creates => "/usr/share/php/PHPUnit"
    }


}