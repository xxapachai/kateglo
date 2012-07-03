class java {
    package {
        "openjdk-7-jdk" :
            ensure => installed,
            require => Exec["apt-get update"]
    }

    package {
        ["openjdk-7-source", "openjdk-7-demo", "openjdk-7-doc", "openjdk-7-jre-headless", "openjdk-7-jre-lib"] :
            ensure => installed,
            require => Package["openjdk-7-jdk"]
    }

}
