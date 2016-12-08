default['nemo']['user'] = 'please_change_me'
default['nemo']['system_user'] = 'vagrant'
default['nemo']['system_group'] = 'vagrant'
default['nemo']['root_directory'] = '/vagrant/www/'
default['nemo']['apache_host'] = '127.0.0.1'
default['nemo']['apache_port'] = '8080'
default['nemo']['varnish_host'] = '127.0.0.1'
default['nemo']['varnish_port'] = '80'

default['apache']['listen']   = ["*:#{node['nemo']['apache_port']}"]
default['apache']['default_site_port']   = ["*:#{node['nemo']['apache_port']}"]


default['nemo']['git_user']			   = "#{node[:nemo][:user]}"
default['nemo']['git_password']		   = 'please_change_me'
default['nemo']['git_site_repo']   = "http://#{node['nemo']['git_user']}:#{node['nemo']['git_password']}@stash.dsg-i.com/scm/ep/fo-site.git";
default['nemo']['git_site_branch']   = 'develop'; 

default['nemo']['git_config_repo']   = "http://#{node['nemo']['git_user']}:#{node['nemo']['git_password']}@stash.dsg-i.com/scm/ep/configuration.git";
default['nemo']['git_config_branch']   = 'master'; 

default['nemo']['git_common_repo']   = "http://#{node['nemo']['git_user']}:#{node['nemo']['git_password']}@stash.dsg-i.com/scm/ep/core-common.git";
default['nemo']['git_common_branch']   = 'master'; 

#service is bugged
default['varnish']['log_daemon'] = false;
default['couchbase']['server']['password'] = '123';
default['postgresql']['password']['postgres'] = '123'
