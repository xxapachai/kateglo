class diverse {
    $packages = ["curl", "vim", "subversion", "git", "libpcre3-dev", "make",
                "augeas-tools", "libaugeas-dev", "libaugeas-ruby"]

    package { $packages:
        ensure => present,
        require => Exec["apt-get update"]
    }

    # After installing augeas and augeas ruby, this step must be taken so
    exec { "ln -s /usr/lib/ruby/1.8/x86_64-linux/_augeas.so /opt/vagrant_ruby/lib/ruby/site_ruby/1.8/x86_64-linux/":
        creates => "/opt/vagrant_ruby/lib/ruby/site_ruby/1.8/x86_64-linux/_augeas.so",
        require => [Package["augeas-tools"], Package["libaugeas-dev"], Package["libaugeas-ruby"]],
    }

    exec { "ln -s /usr/lib/ruby/1.8/augeas.rb /opt/vagrant_ruby/lib/ruby/site_ruby/1.8/":
        creates => "/opt/vagrant_ruby/lib/ruby/site_ruby/1.8/augeas.rb",
        require => [Package["augeas-tools"], Package["libaugeas-dev"], Package["libaugeas-ruby"]],
    }
}