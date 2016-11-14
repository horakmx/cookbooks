#
## Cookbook Name:: sandbox
## Recipe:: www
##
## Copyright (C) 2016 Jean-Marc PULVAR
##
## All rights reserved - Do Not Redistribute
#"

directory "#{node['sandbox']['root_directory']}" do
  owner node['sandbox']['system_user']
  group node['sandbox']['system_group']
  mode '0755'
  action :create
end
