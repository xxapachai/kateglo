class kateglo {

    file { "/home/${globalUser}/log":
        ensure => "directory",
        group => "www-data",
        owner => "www-data",
        require => [Package["apache2"]]
    }

    exec { "checkout kateglo" :
        command => "svn co https://kateglo.googlecode.com/svn/branches/kateglox kateglo",
        cwd => "/home/${globalUser}/",
        creates => "/home/${globalUser}/kateglo",
        timeout => 0,
        require => [Package["apache2"], Package["subversion"]],
    }

    file { "/home/${globalUser}/kateglo":
        ensure => "directory",
        recurse => inf,
        group => "www-data",
        owner => "www-data",
        require => [Exec["checkout kateglo"]]
    }


    file { "/home/${globalUser}/kateglo/cache":
        ensure => "directory",
        recurse => inf,
        group => "www-data",
        owner => "www-data",
        require => [Exec["checkout kateglo"]]
    }

    exec { "get composer" :
        command => "sudo -u \"www-data\" curl -s http://getcomposer.org/installer | sudo -u\"www-data\" php",
        cwd => "/home/${globalUser}/kateglo",
        timeout => 0,
        creates => "/home/${globalUser}/kateglo/composer.phar",
        logoutput => true,
        require => [Package["curl"],Exec["checkout kateglo"], File["/home/${globalUser}/kateglo"]],
    }

    exec { "sudo -u \"www-data\" php composer.phar install" :
        cwd => "/home/${globalUser}/kateglo",
        creates => "/home/${globalUser}/kateglo/vendor",
        timeout => 0,
        logoutput => true,
        require => [Package["php5"],Exec["get composer"]],
        notify => Exec["apache2ctl graceful"],
    }


    $identifiedByPassword = "identified by '${mysqlRootPassword}'"
    exec { "create kateglo db":
      unless => "mysql -u root -p\"${mysqlRootPassword}\" kateglox",
      command => "mysql -u root -p\"${mysqlRootPassword}\" -e \"create database kateglox; grant all on kateglox.* to root ${identifiedByPassword};\"",
      group => "root",
      user => "root",
      require => [Service["mysql"], Package["mysql-server"], Exec["set-mysql-root-password"]],
    }

    if $mysqlUser != "" {
        if $mysqlUserPassword != "" {
            exec { "grant table to user":
              unless => "mysql -u ${mysqlUser} -p\"${mysqlUserPassword}\" kateglox",
              command => "mysql -u root -p\"${mysqlRootPassword}\" -e \"grant all on kateglox.* to ${mysqlUser} identified by '${mysqlUserPassword}'; grant all on kateglox.* to ${mysqlUser}@'localhost' identified by '${mysqlUserPassword}';\"",
              group => "root",
              user => "root",
              require => [Service["mysql"], Package["mysql-server"], Exec["create user"],  Exec["create kateglo db"]],
            }
        }
    }

    file { "/home/${globalUser}/kateglox.sql":
        ensure => present,
        source => "puppet:///modules/kateglo/kateglox.sql",
        require => Exec["create kateglo db"],
        notify => Exec["import kateglo dump"],
    }

    if $mysqlRootPassword != "" {
        exec { "import kateglo dump":
            refreshonly => true,
            command => "mysql -u root -p\"${mysqlRootPassword}\" kateglox < kateglox.sql",
            cwd => "/home/${globalUser}/",
            group => "root", user => "root",
            logoutput => true,
            timeout => 0,
            require => File["/home/${globalUser}/kateglox.sql"],
            subscribe => File["/home/${globalUser}/kateglox.sql"],
        }
    } else {
        exec { "import kateglo dump":
            refreshonly => true,
            command => "mysql -u root kateglox < kateglox.sql",
            cwd => "/home/${globalUser}/",
            group => "root", user => "root",
            logoutput => true,
            timeout => 0,
            require => File["/home/${globalUser}/kateglox.sql"],
            subscribe => File["/home/${globalUser}/kateglox.sql"],
        }
    }

    # ServerAdmin webmaster@localhost
    $serverAdmin = "webmaster@kateglo.com"

    # DocumentRoot /var/www
    $documentRoot = "/home/${globalUser}/kateglo/public"

    # ErrorLog ${APACHE_LOG_DIR}/error.log
    $errorLog = "${APACHE_LOG_DIR}/error.log"

    # Possible values include: debug, info, notice, warn, error, crit,
    # alert, emerg.
    $logLevel = "debug"

    file {"/etc/apache2/sites-available/kateglo":
        content => template("apache/site.erb"),
        owner   => "root",
        group   => "root",
        mode    => 0644,
        require => Package["apache2"],
        ensure => "present",
        notify  => Exec["/usr/sbin/a2ensite kateglo"],
    }

    exec { "/usr/sbin/a2dissite default":
		onlyif => "test -f /etc/apache2/sites-enabled/000-default",
        notify => Exec["apache2ctl graceful"],
        require => File["/etc/apache2/sites-available/kateglo"],
    }

    exec { "/usr/sbin/a2ensite kateglo":
        require => File["/etc/apache2/sites-available/kateglo"],
        creates => "/etc/apache2/sites-enabled/kateglo",
        notify => Exec["apache2ctl graceful"],
    }

    if $mysqlRootPassword != "" {
        if $mysqlUser != "" {
            if $mysqlUserPassword != "" {
                $solrDBUser = $mysqlUser
                $solrDBPassword = $mysqlUserPassword
            }
        }else{
            $solrDBUser = "root"
            $solrDBPassword = $mysqlRootPassword
        }
    } else {
        $solrDBUser = "root"
        $solrDBPassword = ""
    }

    file { "/home/${globalUser}/solr/conf/data-config.xml":
        ensure => present,
        content => template("kateglo/data-config.xml.erb"),
        owner => "jetty", group => "jetty",
        mode => "0644",
        require => File["/etc/default/jetty"],
        notify => Service["jetty"],
    }

    file { "/home/${globalUser}/kateglo/application/configs/application.ini":
        ensure => present,
        content => template("kateglo/application.ini.erb"),
        owner => "www-data", group => "www-data",
        mode => "0644",
        require => [Exec["checkout kateglo"], File["/home/${globalUser}/kateglo"]],
    }

    file { "/home/${globalUser}/solr/conf/elevate.xml":
        ensure => present,
        source => "puppet:///modules/kateglo/elevate.xml",
        owner => "jetty", group => "jetty",
        mode => "0644",
        require => File["/etc/default/jetty"],
        notify => Service["jetty"],
    }

    file { "/home/${globalUser}/solr/conf/protwords.txt":
        ensure => present,
        source => "puppet:///modules/kateglo/protwords.txt",
        owner => "jetty", group => "jetty",
        mode => "0644",
        require => File["/etc/default/jetty"],
        notify => Service["jetty"],
    }

    file { "/home/${globalUser}/solr/conf/schema.xml":
        ensure => present,
        source => "puppet:///modules/kateglo/schema.xml",
        owner => "jetty", group => "jetty",
        mode => "0644",
        require => File["/etc/default/jetty"],
        notify => Service["jetty"],
    }

    file { "/home/${globalUser}/solr/conf/solrconfig.xml":
        ensure => present,
        content => template("kateglo/solrconfig.xml.erb"),
        owner => "jetty", group => "jetty",
        mode => "0644",
        require => File["/etc/default/jetty"],
        notify => Service["jetty"],
    }

    file { "/home/${globalUser}/solr/conf/spellings.txt":
        ensure => present,
        source => "puppet:///modules/kateglo/spellings.txt",
        owner => "jetty", group => "jetty",
        mode => "0644",
        require => File["/etc/default/jetty"],
        notify => Service["jetty"],
    }

    file { "/home/${globalUser}/solr/conf/stopwords.txt":
        ensure => present,
        source => "puppet:///modules/kateglo/stopwords.txt",
        owner => "jetty", group => "jetty",
        mode => "0644",
        require => File["/etc/default/jetty"],
        notify => Service["jetty"],
    }

    file { "/home/${globalUser}/solr/conf/synonyms.txt":
        ensure => present,
        source => "puppet:///modules/kateglo/synonyms.txt",
        owner => "jetty", group => "jetty",
        mode => "0644",
        require => File["/etc/default/jetty"],
        notify => Service["jetty"],
    }

    exec{ "restart jetty":
        require => [File["/home/${globalUser}/solr/conf/synonyms.txt"], File["/home/${globalUser}/solr/conf/stopwords.txt"],
                    File["/home/${globalUser}/solr/conf/spellings.txt"], File["/home/${globalUser}/solr/conf/solrconfig.xml"],
                    File["/home/${globalUser}/solr/conf/schema.xml"], File["/home/${globalUser}/solr/conf/protwords.txt"],
                    File["/home/${globalUser}/solr/conf/elevate.xml"], File["/home/${globalUser}/solr/conf/data-config.xml"],
                    Exec["import kateglo dump"], Service["jetty"]],
        command => "sudo service jetty restart",
    }

    exec {"wait for jetty":
      require => [Service["jetty"], Exec["restart jetty"]],
      command => "wget --spider --tries 50 --retry-connrefused --no-check-certificate http://localhost:8080/solr/",
    }

    exec { "solr full import":
        command => "sudo curl http://127.0.0.1:8080/solr/dataimport?command=full-import",
        logoutput => true,
        unless => "test -f /home/${globalUser}/solr/data/index/*.fdx",
        require => [File["/home/${globalUser}/solr/conf/synonyms.txt"], File["/home/${globalUser}/solr/conf/stopwords.txt"],
            File["/home/${globalUser}/solr/conf/spellings.txt"], File["/home/${globalUser}/solr/conf/solrconfig.xml"],
            File["/home/${globalUser}/solr/conf/schema.xml"], File["/home/${globalUser}/solr/conf/protwords.txt"],
            File["/home/${globalUser}/solr/conf/elevate.xml"], File["/home/${globalUser}/solr/conf/data-config.xml"],
            Exec["import kateglo dump"], Service["jetty"], Exec["wait for jetty"], Exec["restart jetty"], Service["mysql"]],

    }

}