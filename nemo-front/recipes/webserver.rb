#
# Cookbook Name:: nemo-front
# Recipe:: default
#
# Copyright (C) 2014 YOUR_NAME
#
# All rights reserved - Do Not Redistribute
#

group node['nemo']['group']

user node['nemo']['user'] do
  supports :manage_home => true 
  group node['nemo']['group']
  shell '/bin/bash'
  home "/home/#{node['nemo']['user']}"
  action :create
end

include_recipe 'apache2'
include_recipe 'apache2::mod_actions'
include_recipe 'apache2::mod_suexec'
include_recipe 'apache2::mod_fastcgi'
include_recipe 'apache2::mod_vhost_alias'

template "#{node['apache']['dir']}/ports.conf" do
  source 'ports.conf.erb'
end

# create apache config
web_app  "#{node['nemo']['user']}" do
  template 'apache2.conf.erb'
  enable true
end

# disable default site
apache_site '000-default' do
  enable false
end

#Additional modules
# using apt

#updating packages before installing memcache modules otherwise it fails
bash "update packages" do
user "root"
code <<-EOF
 apt-get update --fix-missing
EOF
end

package "php5-memcache" do
  action :install
 end

package "apache2-suexec" do
  action :install
 end

include_recipe 'curl'
package "php5-curl" do
  action :install
 end

#required for sql relay
package "libssl0.9.8" do
	action :install
end
