class solr {
    package {
        "jetty" :
            ensure => installed,
            require => Exec["apt-get update"]
    }
	
	service { 
		'jetty' :
			enable    => true,
			ensure    => running,
			subscribe => [Package['jetty']]
	}

}