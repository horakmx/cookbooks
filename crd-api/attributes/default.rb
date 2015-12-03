default['crd-api']['app_name'] = 'crd-api'
default['crd-api']['system_user'] = 'vagrant'
default['crd-api']['system_group'] = 'vagrant'
default['crd-api']['root_directory'] = "/vagrant/www/"
default['crd-api']['apache_host'] = '127.0.0.1'
default['crd-api']['apache_port'] = '80'

default['php-phalcon']['git_url'] = 'git://github.com/phalcon/cphalcon.git'
default['php-phalcon']['git_ref'] = 'master'

case platform_family
when "rhel", "fedora"

    default['php-phalcon']['packages'] = ['git', 'php-devel', 'pcre-devel', 'gcc', 'make']
    default['php-phalcon']['conf_dirs'] = ['/etc/php.d']
    default['php-phalcon']['conf_file'] = 'phalcon.ini'

when "debian"

    default['php-phalcon']['packages'] = ['git', 'php5-dev', 'libpcre3-dev', 'gcc', 'make', 'php5-mysql']
    default['php-phalcon']['conf_dirs'] = ['/etc/php5/fpm/conf.d/']
    default['php-phalcon']['conf_cli_dirs'] = ['/etc/php5/cli/conf.d']
    default['php-phalcon']['conf_file'] = '30-phalcon.ini'

end

default['php-phalcon']['devtools'] = true
