default['crd-api']['app_name'] = 'crd-api'
default['crd-api']['system_user'] = 'vagrant'
default['crd-api']['system_group'] = 'vagrant'
default['crd-api']['base_directory'] = "/vagrant/app/"
default['crd-api']['root_directory'] = "/vagrant/app/www/"
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

default['xdebug']['version'] = '2.2.3'
default['xdebug']['config_file'] = '/etc/php5/fpm/conf.d/xdebug.ini'
default['xdebug']['web_server']['service_name'] = 'apache2'
default['xdebug']['directives'] = 
{
    'profiler_output_dir' 		=> "/vagrant/app/xdebug",
    'remote_port' 				=> "9000",
    'remote_connect_back' 		=> "1",
    'remote_enable' 			=> "1",
    'profiler_enable_trigger' 	=> "1",
    'collect_assignments' 		=> "1",
    'collect_includes' 			=> "1",
    'collect_params' 			=> "4",
    'collect_return' 			=> "1",
    'show_mem_delta' 			=> "1",
    'trace_output_name' 		=> "trace.%R",
    'trace_enable_trigger'      => "1"
}

#default['bamboo']['home_dir'] = 
#default['bamboo']['data_dir'] = 
#default['bamboo']['user'] = 
#default['bamboo']['group'] = 
#default['bamboo']['disable_agent_auto_capability_detection'] = false
#default['bamboo']['additional_path'] = ''
default['bamboo']['database']['type'] = 'mysql'
default['bamboo']['backup']['enabled'] = false
default['bamboo']['graylog']['enabled'] = false
