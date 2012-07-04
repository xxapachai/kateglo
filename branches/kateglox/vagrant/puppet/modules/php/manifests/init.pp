class php {
    package {
        "php5" :
            ensure => installed,
            require => Exec["apt-get update"]
    }

    package {
        ["php5-cli", "php-pear", "php5-suhosin", "php5-xdebug", "php-apc",
        "php5-curl", "php5-gd", "php5-mysql", "php5-xsl", "libapache2-mod-php5",
        "php5-tidy", "apache2-mpm-prefork"] :
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

    exec { "pecl install apc":
        require => [Package['make'], Package['libpcre3-dev'], Package['php-apc']],
        onlyif => "test -z 'pecl info apc'",
        notify  => Exec['apache2ctl graceful'],
    }

    file {"/etc/php5/conf.d/apc.ini":
        content => template('php/apc.ini.erb'),
        owner   => 'root',
        group   => 'root',
        mode    => 0644,
        require => Package['php-apc'],
        ensure => 'present',
        notify  => Exec['apache2ctl graceful'],
    }

    file {"/etc/php5/conf.d/xdebug.ini":
        content => template('php/xdebug.ini.erb'),
        owner   => 'root',
        group   => 'root',
        mode    => 0644,
        require => Package['php5-xdebug'],
        ensure => 'present',
        notify  => Exec['apache2ctl graceful'],
    }

    # Maximum amount of memory a script may consume (128MB)
    # http://php.net/memory-limit
    # memory_limit = 128M
    $memoryLimit = '256M'

    # Common Values:
    #   E_ALL & ~E_NOTICE  (Show all errors, except for notices and coding standards warnings.)
    #   E_ALL & ~E_NOTICE | E_STRICT  (Show all errors, except for notices)
    #   E_COMPILE_ERROR|E_RECOVERABLE_ERROR|E_ERROR|E_CORE_ERROR  (Show only errors)
    #   E_ALL | E_STRICT  (Show all errors, warnings and notices including coding standards.)
    # Default Value: E_ALL & ~E_NOTICE
    # Development Value: E_ALL | E_STRICT
    # Production Value: E_ALL & ~E_DEPRECATED
    # http://php.net/error-reporting
    # error_reporting = E_ALL & ~E_DEPRECATED
    $errorReporting = 'E_ALL | E_STRICT'

    # Possible Values:
    #   Off = Do not display any errors
    #   stderr = Display errors to STDERR (affects only CGI/CLI binaries!)
    #   On or stdout = Display errors to STDOUT
    # Default Value: On
    # Development Value: On
    # Production Value: Off
    # http://php.net/display-errors
    # display_errors = Off
    $displayErrors = 'On'

    # Default Value: Off
    # Development Value: On
    # Production Value: Off
    # http://php.net/display-startup-errors
    # display_startup_errors = Off
    $displayStartupErrors = 'On'

    # Default Value: Off
    # Development Value: On
    # Production Value: Off
    # http://php.net/track-errors
    # track_errors = Off
    $trackErrors = 'On'

    # Maximum size of POST data that PHP will accept.
    # http://php.net/post-max-size
    # post_max_size = 8M
    $postMaxSize = '64M'

    # Maximum allowed size for uploaded files.
    # http://php.net/upload-max-filesize
    # upload_max_filesize = 2M
    $uploadMaxFilesize = '64M'

    file {"/etc/php5/apache2/php.ini":
        content => template('php/php.ini.erb'),
        owner   => 'root',
        group   => 'root',
        mode    => 0644,
        require => Package['php5'],
        ensure => 'present',
        notify  => Exec['apache2ctl graceful'],
    }

}