default['sandbox']['port'] = 223
default['sandbox']['user'] = 'sandbox'
default['sandbox']['group'] = "#{node['sandbox']['user']}"
default['sandbox']['system_user'] = "#{node['sandbox']['user']}"
default['sandbox']['system_group'] = "#{node['sandbox']['user']}"
default['sandbox']['root_directory'] = "/home/#{node['sandbox']['user']}/www/"
default['sandbox']['apache_host'] = '138.68.175.5'
default['sandbox']['domain'] = 'sandbox.gtfooh.org'
default['sandbox']['apache_port'] = '80'

#php fpm
default['php-fpm']['package_name'] = 'php-fpm'
default['php-fpm']['service_name'] = 'php7.0-fpm'
default['php-fpm']['base_dir'] = '/etc/php/7.0/fpm'
default['php-fpm']['conf_dir'] = "#{node['php-fpm']['base_dir']}/conf.d"
default['php-fpm']['conf_file'] = "#{node['php-fpm']['base_dir']}/php-fpm.conf"
default['php-fpm']['pool_conf_dir'] = "#{node['php-fpm']['base_dir']}/pool.d"

#php
default['php']['install_method'] = 'package'
default['php']['configure_options'] = %W(--prefix=#{node['php']['prefix_dir']}
                                         --with-libdir=lib
                                         --with-config-file-path=#{node['php']['conf_dir']}
                                         --with-config-file-scan-dir=#{node['php']['ext_conf_dir']}
                                         --with-pear
                                         --enable-fpm
                                         --with-fpm-user=#{node['php']['fpm_user']}
                                         --with-fpm-group=#{node['php']['fpm_group']}
                                         --with-zlib
                                         --with-openssl
                                         --with-kerberos
                                         --with-bz2
                                         --with-curl
                                         --enable-ftp
                                         --enable-zip
                                         --with-gettext
                                         --with-gmp
                                         --with-mhash
                                         --with-iconv
                                         --enable-sockets
                                         --with-libevent-dir
                                         --with-mcrypt
                                         --enable-mbstring
                                         --with-t1lib
                                         )
