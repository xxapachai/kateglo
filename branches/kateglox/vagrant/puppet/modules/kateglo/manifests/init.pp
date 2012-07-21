class kateglo {

    file { '/home/vagrant/log':
        ensure => 'directory',
        group => 'www-data',
        owner => 'www-data',
        require => [Package['apache2']]
    }

    exec { "checkout kateglo" :
        command => "svn co https://kateglo.googlecode.com/svn/branches/kateglox kateglo",
        cwd => '/home/vagrant/',
        creates => '/home/vagrant/kateglo',
        timeout => 0,
        require => [Package['apache2'], Package['subversion']],
    }

    file { '/home/vagrant/kateglo':
        ensure => 'directory',
        recurse => inf,
        group => 'www-data',
        owner => 'www-data',
        require => [Exec["checkout kateglo"]]
    }


    file { '/home/vagrant/kateglo/cache':
        ensure => 'directory',
        group => 'www-data',
        owner => 'www-data',
        require => [Exec["checkout kateglo"]]
    }

    exec { 'curl -s http://getcomposer.org/installer | php' :
        cwd => '/home/vagrant/kateglo',
        user => 'www-data',
        group => 'www-data',
        timeout => 0,
        creates => '/home/vagrant/kateglo/composer.phar',
        logoutput => true,
        require => [Package['curl'],Exec["checkout kateglo"]],
    }

    exec { 'php composer.phar install' :
        cwd => '/home/vagrant/kateglo',
        creates => '/home/vagrant/kateglo/vendor',
        user => "root",
        group => "root",
        timeout => 0,
        logoutput => true,
        require => [Package['php5'],Exec['curl -s http://getcomposer.org/installer | php']],
        notify => Exec["apache2ctl graceful"],
    }

    exec { "create kateglo db":
      unless => "mysql -u root kateglox",
      command => "mysql -u root -e \"create database kateglox; grant all on kateglox.* to root@% ;\"",
      group => "root",
      user => "root",
      require => [Service["mysql"], Package["mysql-server"]],
    }

    file { "/home/vagrant/kateglox.sql":
        ensure => present,
        source => "puppet:///modules/kateglo/kateglox.sql",
        require => Exec["create kateglo db"],
        notify => Exec["import kateglo dump"],
    }

    exec { "import kateglo dump":
        refreshonly => true,
        command => "mysql -u root kateglox < kateglox.sql",
        cwd => "/home/vagrant/",
        timeout => 0,
        require => File["/home/vagrant/kateglox.sql"],
        subscribe => File["/home/vagrant/kateglox.sql"],
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

    file { "/home/vagrant/solr/conf/data-config.xml":
        ensure => present,
        source => "puppet:///modules/kateglo/data-config.xml",
        owner => "jetty", group => "jetty",
        mode => "0644",
        require => File["/etc/default/jetty"],
        notify => Service["jetty"],
    }

    file { "/home/vagrant/solr/conf/elevate.xml":
        ensure => present,
        source => "puppet:///modules/kateglo/elevate.xml",
        owner => "jetty", group => "jetty",
        mode => "0644",
        require => File["/etc/default/jetty"],
        notify => Service["jetty"],
    }

    file { "/home/vagrant/solr/conf/protwords.txt":
        ensure => present,
        source => "puppet:///modules/kateglo/protwords.txt",
        owner => "jetty", group => "jetty",
        mode => "0644",
        require => File["/etc/default/jetty"],
        notify => Service["jetty"],
    }

    file { "/home/vagrant/solr/conf/schema.xml":
        ensure => present,
        source => "puppet:///modules/kateglo/schema.xml",
        owner => "jetty", group => "jetty",
        mode => "0644",
        require => File["/etc/default/jetty"],
        notify => Service["jetty"],
    }

    file { "/home/vagrant/solr/conf/solrconfig.xml":
        ensure => present,
        source => "puppet:///modules/kateglo/solrconfig.xml",
        owner => "jetty", group => "jetty",
        mode => "0644",
        require => File["/etc/default/jetty"],
        notify => Service["jetty"],
    }

    file { "/home/vagrant/solr/conf/spellings.txt":
        ensure => present,
        source => "puppet:///modules/kateglo/spellings.txt",
        owner => "jetty", group => "jetty",
        mode => "0644",
        require => File["/etc/default/jetty"],
        notify => Service["jetty"],
    }

    file { "/home/vagrant/solr/conf/stopwords.txt":
        ensure => present,
        source => "puppet:///modules/kateglo/stopwords.txt",
        owner => "jetty", group => "jetty",
        mode => "0644",
        require => File["/etc/default/jetty"],
        notify => Service["jetty"],
    }

    file { "/home/vagrant/solr/conf/synonyms.txt":
        ensure => present,
        source => "puppet:///modules/kateglo/synonyms.txt",
        owner => "jetty", group => "jetty",
        mode => "0644",
        require => File["/etc/default/jetty"],
        notify => Service["jetty"],
    }

    #exec { "solr full import":
    #    command => "curl http://127.0.0.1:8080/solr/dataimport?command=full-import",
    #    refreshonly => true,
    #    owner => "jetty", group => "jetty",
    #    subscribe => [File["/home/vagrant/solr/conf/synonyms.txt"], File["/home/vagrant/solr/conf/stopwords.txt"],
    #         File["/home/vagrant/solr/conf/spellings.txt"], File["/home/vagrant/solr/conf/solrconfig.xml"],
    #         File["/home/vagrant/solr/conf/schema.xml"], File["/home/vagrant/solr/conf/protwords.txt"],
    #         File["/home/vagrant/solr/conf/elevate.xml"], File["/home/vagrant/solr/conf/data-config.xml"]],
    #    require => [File["/home/vagrant/solr/conf/synonyms.txt"], File["/home/vagrant/solr/conf/stopwords.txt"],
    #        File["/home/vagrant/solr/conf/spellings.txt"], File["/home/vagrant/solr/conf/solrconfig.xml"],
    #        File["/home/vagrant/solr/conf/schema.xml"], File["/home/vagrant/solr/conf/protwords.txt"],
    #        File["/home/vagrant/solr/conf/elevate.xml"], File["/home/vagrant/solr/conf/data-config.xml"], Exec["import kateglo dump"]],
    #
    #}

}