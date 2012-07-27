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


    exec { "set-mysql-root-password":
        unless => "mysqladmin -uroot -p\"${mysqlRootPassword}\" status",
        command => "mysqladmin -uroot password \"${mysqlRootPassword}\"",
        require => Service["mysql"],
        group => "root",
        user =>  "root",
    }


    if $mysqlUser != "" {
        if $mysqlUserPassword != "" {
            exec { "create user":
                unless => "mysqladmin -u${mysqlUser} ${mysqlAddPassword} status",
                command => "mysql -u root -p\"${mysqlRootPassword}\" -e \"create user ${mysqlUser} identified by ${mysqlUserPassword};\"",
                group => "root",
                user => "root",
                require => [Service["mysql"], Package["mysql-server"], Exec["set-mysql-root-password"]],
            }
        }
    }
}