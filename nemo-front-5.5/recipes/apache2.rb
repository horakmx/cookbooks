#
# Cookbook Name:: nemo-front
# Recipe:: default
#
# Copyright (C) 2014 YOUR_NAME
#
# All rights reserved - Do Not Redistribute
#

#updating packages before installing apache

bash "update packages" do
user "root"
code <<-EOF
 apt-get update --fix-missing
 EOF
end

group node['nemo']['system_group']

user node['nemo']['system_user'] do
  supports :manage_home => true 
  group node['nemo']['system_group']
  shell '/bin/bash'
  home "/home/#{node['nemo']['system_user']}"
  action :create
end

include_recipe 'apache2'
include_recipe 'apache2::mod_actions'
include_recipe 'apache2::mod_suexec'
include_recipe 'apache2::mod_fastcgi'
include_recipe 'apache2::mod_vhost_alias'

# create apache config
web_app  "#{node['nemo']['user']}" do
  template 'apache2.conf.erb'
  enable true
end

# disable default site
apache_site '000-default' do
  enable false
end

template "#{node['apache']['conf_dir']}/ports.conf" do
  source 'ports.conf.erb'
end
