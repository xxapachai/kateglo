class mysql {

    package { "mysql-server":
        ensure => present,
        require => Exec["apt-get update"]
    }

    service { "mysql":
        ensure => running,
        require => File["/etc/mysql/my.cnf"],
    }

    file { "/etc/mysql/my.cnf":
        ensure => present,
        group => "root",
        owner => "root",
        mode => "0644",
        source => "puppet:///modules/mysql/my.cnf",
        require => Package["mysql-server"],
        notify => Service["mysql"],
    }
}