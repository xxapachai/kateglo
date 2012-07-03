# default path
Exec {
  path => ["/usr/bin", "/bin", "/usr/sbin", "/sbin", "/usr/local/bin", "/usr/local/sbin"]
}

node default{
    include bootstrap
    include diverse
    include apache
    include php
    include mysql
	include java
	include solr
}
