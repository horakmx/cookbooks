default['nemo']['user'] = 'please_fill_me_in'
default['nemo']['system_user'] = 'vagrant'
default['nemo']['system_group'] = 'vagrant'
default['nemo']['root_directory'] = "/vagrant/www/"
default['nemo']['apache_host'] = '127.0.0.1'
default['nemo']['apache_port'] = '8080'
default['nemo']['varnish_host'] = '127.0.0.1'
default['nemo']['varnish_port'] = '80'

default['nemo']['svn_user']			   = "#{node[:nemo][:user]}"
default['nemo']['svn_password']		   = 'please_fill_me_in'
default['nemo']['svn_currys_branch']   = 'https://svn.dsg-i.com/repo/fo-currys/branches/release4_trunk/src';
