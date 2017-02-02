default['sandbox']['port'] = 223
default['sandbox']['user'] = 'sandbox'
default['sandbox']['group'] = "#{node['sandbox']['user']}"
default['sandbox']['system_user'] = "#{node['sandbox']['user']}"
default['sandbox']['system_group'] = "#{node['sandbox']['user']}"
default['sandbox']['root_directory'] = "/home/#{node['sandbox']['user']}/www/"
default['sandbox']['apache_host'] = '188.166.170.23'
default['sandbox']['domain'] = '188.166.170.23'
#default['sandbox']['domain'] = 'sandbox.gtfooh.org'
default['sandbox']['apache_port'] = '80'
default['sandbox']['mysql_host'] = 'localhost'
default['sandbox']['mysql_port'] = '3306'
default['sandbox']['mysql_version'] = '5.7'
default['sandbox']['mysql_password'] = 'test123'

#- Location to place WordPress files.
default['sandbox']['wordpress_dir'] = "#{node['sandbox']['root_directory']}/wordpress"
default['sandbox']['wordpress_mysql_database'] = "wordpress"
default['sandbox']['wordpress_mysql_username'] = "wordpress"
default['sandbox']['wordpress_mysql_password'] = "wordpress123"

#php fpm
default['php-fpm']['package_name'] = 'php-fpm'
default['php-fpm']['service_name'] = 'php7.0-fpm'
default['php-fpm']['base_dir'] = '/etc/php/7.0/fpm'
default['php-fpm']['conf_dir'] = "#{node['php-fpm']['base_dir']}/conf.d"
default['php-fpm']['conf_file'] = "#{node['php-fpm']['base_dir']}/php-fpm.conf"
default['php-fpm']['pool_conf_dir'] = "#{node['php-fpm']['base_dir']}/pool.d"

#php
default['php']['install_method'] = 'package'

#java

default['java']['jdk_version'] = '8'

#Kibana
default['kibana']['install_method'] = 'package'
default['kibana']['repository_url'] = 'https://artifacts.elastic.co/packages/5.x/apt'
default['kibana']['repository_key'] = 'https://artifacts.elastic.co/GPG-KEY-elasticsearch'
default['kibana']['version'] = '5'
default['kibana']['interface'] = '127.0.0.1'
default['kibana']['port'] = '5602'
default['kibana']['elasticsearch']['port'] = '9201'
default['kibana']['elasticsearch']['hosts'] = ['127.0.0.1']
default['kibana']['base_dir'] = '/etc/kibana/'

default['kibana']['kibana_service'] = 'http://127.0.0.1:5602'
#<> Enable http auth for Apache
default['kibana']['apache']['basic_auth'] = 'on'
##<> Apache http auth username
default['kibana']['apache']['basic_auth_username'] = 'admin'
##<> Apache http auth password
default['kibana']['apache']['basic_auth_password'] = 'admin123'
##<> The port on which to bind apache.
default['kibana']['apache']['port'] = 5603
##<> Boolean switch to enable apache search query proxy
default['kibana']['apache']['proxy'] = true
##<> The apache configuration source
default['kibana']['apache']['cookbook'] = 'kibana'
