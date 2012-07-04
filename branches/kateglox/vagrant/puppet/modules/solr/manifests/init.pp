class solr {
    package {
        "jetty" :
            ensure => installed,
            require => [Package["openjdk-7-jdk"], Exec["apt-get update"]]
    }

    file {"/etc/default/jetty":
        content => template('solr/default.jetty.erb'),
        owner   => 'root',
        group   => 'root',
        mode    => 0644,
        require => Package['jetty'],
        ensure => 'present',
        notify  => Service['jetty'],
    }
	
	service { 
		'jetty' :
			enable    => true,
			ensure    => running,
			subscribe => [Package['jetty'], File['/etc/default/jetty']]
	}

}