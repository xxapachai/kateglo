class diverse {
    $packages = ["curl", "vim", "subversion", "git", "libpcre3-dev", "make",
                "augeas-tools", "libaugeas-dev", "libaugeas-ruby"]

    package { $packages:
        ensure => present,
        require => Exec["apt-get update"]
    }

}