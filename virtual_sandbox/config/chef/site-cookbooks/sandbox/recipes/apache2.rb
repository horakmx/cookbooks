#
# Cookbook Name:: sandbox
# Recipe:: apache2
#
# Copyright (C) 2016 Jean-Marc PULVAR
#
# All rights reserved - Do Not Redistribute
#

group node['sandbox']['group']

user node['sandbox']['user'] do
  supports :manage_home => true 
  group node['sandbox']['group']
  shell '/bin/bash'
  home "/home/#{node['sandbox']['user']}"
  action :create
end

include_recipe 'apache2'
#installing modules
include_recipe 'apache2::mod_actions'

package 'apache2-suexec-custom' do
action :install
end

include_recipe 'apache2::mod_suexec'
include_recipe 'apache2::mod_fastcgi'
include_recipe 'apache2::mod_vhost_alias'
include_recipe 'apache2::mod_authn_core'
include_recipe 'apache2::mod_authz_core'

template "#{node['apache']['dir']}/ports.conf" do
  source 'ports.conf.erb'
end

# create apache config
web_app  "#{node['sandbox']['user']}" do
  template 'apache2.conf.erb'
  enable true
end

# disable default site
apache_site '000-default' do
  enable false
end

