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

    $solrPackage =  'apache-solr-3.6.0'

	exec {"solr wget":
        command => "/usr/bin/wget http://ftp-stud.hs-esslingen.de/pub/Mirrors/ftp.apache.org/dist/lucene/solr/3.6.0/${solrPackage}.tgz -O /home/vagrant/downloads/${solrPackage}.tgz",
        unless  => "test -f /home/vagrant/downloads/${solrPackage}.tgz",
        require => [ Package["jetty"] ],
    }

    exec {"solr extract":
        cwd     => "/home/vagrant/downloads",
        command => "/bin/tar xvzf /home/vagrant/downloads/${solrPackage}.tgz",
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
		recurse => true,
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

}