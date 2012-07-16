class kateglo {

    # create kateglo directory
    file { '/home/vagrant/kateglo':
        ensure => 'directory',
        group => 'vagrant',
        owner => 'vagrant',
        require => [Package['apache2'], Package['subversion']]
    }

    exec { 'svn co https://kateglo.googlecode.com/svn/branches/kateglox .' :
        cwd => '/home/vagrant/kateglo',
        user => 'vagrant',
        group => 'vagrant',
        creates => '/home/vagrant/kateglo',
        require => File['/home/vagrant/kateglo'],
    }

    exec { 'curl -s http://getcomposer.org/installer | php' :
        cwd => '/home/vagrant/kateglo',
        user => 'vagrant',
        group => 'vagrant',
        creates => '/home/vagrant/kateglo/composer.phar',
        require => [Package['curl'],Exec['svn co https://kateglo.googlecode.com/svn/branches/kateglox .']],
    }

    exec { 'php composer.phar install' :
        cwd => '/home/vagrant/kateglo',
        user => 'vagrant',
        group => 'vagrant',
        creates => '/home/vagrant/kateglo/vendor',
        require => [Package['php5'],Exec['curl -s http://getcomposer.org/installer | php']],
        notify => Exec["apache2ctl graceful"],
    }

    # ServerAdmin webmaster@localhost
    $serverAdmin = 'webmaster@kateglo.com'

    # DocumentRoot /var/www
    $documentRoot = '/home/vagrant/kateglo/public'

    # ErrorLog ${APACHE_LOG_DIR}/error.log
    $errorLog = '${APACHE_LOG_DIR}/error.log'

    # Possible values include: debug, info, notice, warn, error, crit,
    # alert, emerg.
    $logLevel = 'debug'

    file {"/etc/apache2/sites-available/kateglo":
        content => template('apache/site.erb'),
        owner   => 'root',
        group   => 'root',
        mode    => 0644,
        require => Package['apache2'],
        ensure => 'present',
        notify  => Exec['/usr/sbin/a2ensite kateglo'],
    }

    exec { "/usr/sbin/a2dissite default":
		onlyif => "test -f /etc/apache2/sites-enabled/000-default",
        notify => Exec["apache2ctl graceful"],
        require => File["/etc/apache2/sites-available/kateglo"],
    }

    exec { "/usr/sbin/a2ensite kateglo":
        require => File["/etc/apache2/sites-available/kateglo"],
        creates => '/etc/apache2/sites-enabled/kateglo',
        notify => Exec["apache2ctl graceful"],
    }


}