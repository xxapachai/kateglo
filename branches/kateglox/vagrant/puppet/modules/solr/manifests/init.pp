class solr {
    package {
        "jetty" :
            ensure => installed,
            require => [Package["openjdk-7-jdk"], Exec["apt-get update"]]
    }

    package {
        ["libjetty-extra-java", "libjetty-java"] :
            ensure => installed,
            require => Package["jetty"]
    }
	
	service { 
		'jetty' :
			enable    => true,
			ensure    => running,
			subscribe => [File['/etc/default/jetty']],
	}

	$mysqlConnector = 'mysql-connector-java-5.1.21'

	exec { "mysql connector" :
	    cwd => "/home/vagrant/downloads/",
	    command => "wget http://ftp.dc.aleron.net/pub/mysql/Connector-J/${mysqlConnector}.tar.gz",
	    unless => "test -f /home/vagrant/downloads/${mysqlConnector}.tar.gz",
	    timeout => 0,
	    require => [Package["jetty"], Package["mysql-server"], File["/home/vagrant/downloads"]]
	}

	exec { "mysql connector extract":
        cwd     => "/home/vagrant/downloads",
        command => "tar -xvzf /home/vagrant/downloads/${mysqlConnector}.tar.gz",
        unless  => "test -d /home/vagrant/downloads/${mysqlConnector}",
        require => [ Exec["mysql connector"] ],
	}

    exec {"mysql connector link library":
        cwd => "/home/vagrant/downloads/${mysqlConnector}/",
        command => "ln -s ${mysqlConnector}-bin.jar /usr/share/jetty/lib/",
        unless => "test -h /usr/share/jetty/lib/${mysqlConnector}-bin.jar",
        require => [Exec["mysql connector extract"]],
    }

    $solrPackage =  'apache-solr-3.6.0'

	exec {"solr wget":
	    cwd => "/home/vagrant/downloads",
        command => "wget http://mirror.softaculous.com/apache/lucene/solr/3.6.0/${solrPackage}.tgz",
        unless  => "test -f /home/vagrant/downloads/${solrPackage}.tgz",
        timeout => 0,
        require => [ Package["jetty"], File["/home/vagrant/downloads"] ],
    }

    exec {"solr extract":
        cwd     => "/home/vagrant/downloads",
        command => "tar -xvzf /home/vagrant/downloads/${solrPackage}.tgz",
        unless  => "test -d /home/vagrant/downloads/${solrPackage}",
        require => [ Package["jetty"], Exec["solr wget"] ],
    }

    exec {"solr move":
        cwd     => "/home/vagrant/downloads/${solrPackage}/example",
        command => "cp -r solr /home/vagrant/",
        unless  => "test -d /home/vagrant/solr",
        require => [ Package["jetty"], Exec["solr extract"] ],
    }
	
	file { "/home/vagrant/solr":
		ensure => directory,
		recurse => inf,
		owner => "jetty",
		group => "jetty",
		require => Exec["solr move"]
	}

    exec {"solr dist move":
        cwd     => "/home/vagrant/downloads/${solrPackage}/",
        command => "cp -r dist /home/vagrant/solr",
        unless  => "test -d /home/vagrant/solr/dist",
        require => [ Package["jetty"], Exec["solr move"] ],
    }

    exec {"solr link library":
        cwd => "/home/vagrant/solr/dist/",
        command => "ln -s apache-solr-dataimporthandler-* /usr/share/jetty/lib/",
        unless => "test -h /usr/share/jetty/lib/apache-solr-dataimporthandler-3.6.0.jar",
        require => [Exec["solr dist move"]],
    }

    exec {"solr copy war":
        cwd => "/home/vagrant/solr/dist/",
        command => "cp ${solrPackage}.war /var/lib/jetty/webapps",
        creates => "/var/lib/jetty/webapps/${solrPackage}.war",
		unless => "test -f /var/lib/jetty/webapps/solr.war",
        require => [Exec["solr link library"]],
    }
	
	exec {"solr rename":
        cwd => "/var/lib/jetty/webapps/",
        command => "mv ${solrPackage}.war solr.war",
        creates => "/var/lib/jetty/webapps/solr.war",
        require => [Exec["solr copy war"]],
    }

    file {"/etc/default/jetty":
        content => template('solr/default.jetty.erb'),
        owner   => 'root',
        group   => 'root',
        mode    => 0644,
        require => Exec['solr rename'],
        ensure => 'present',
    }

    exec {"mysql connector copy library":
        cwd => "/home/vagrant/downloads/${mysqlConnector}/",
        command => "cp ${mysqlConnector}-bin.jar /home/vagrant/solr/dist/",
        unless => "test -f /home/vagrant/solr/dist/${mysqlConnector}-bin.jar",
        require => [Exec["mysql connector extract"], Exec["solr copy war"]],
    }

}