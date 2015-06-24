#
# Cookbook Name:: webpagetest-front
# Recipe:: default
#
# Copyright (C) 2014 YOUR_NAME
#
# All rights reserved - Do Not Redistribute
#

group node['webpagetest']['group']

user node['webpagetest']['user'] do
  supports :manage_home => true 
  group node['webpagetest']['group']
  shell '/bin/bash'
  home "/home/#{node['webpagetest']['user']}"
  action :create
end

include_recipe 'apache2'
include_recipe 'apache2::mod_actions'
include_recipe 'apache2::mod_suexec'
include_recipe 'apache2::mod_fastcgi'
include_recipe 'apache2::mod_vhost_alias'
include_recipe 'apache2::mod_authn_core'
include_recipe 'apache2::mod_authz_core'

template "#{node['apache']['dir']}/ports.conf" do
  source 'ports.conf.erb'
end

# create apache config
web_app  "#{node['webpagetest']['user']}" do
  template 'apache2.conf.erb'
  enable true
end

# disable default site
apache_site '000-default' do
  enable false
end

