#
## Cookbook Name:: sandbox
## Recipe:: php
##
## Copyright (C) 2016 Jean-Marc PULVAR
##
## All rights reserved - Do Not Redistribute
##

include_recipe "php"

php_fpm_pool "#{node['sandbox']['system_user']}" do
action :install
end

#installing building tools
#package "build-essential" do
#   action :install
#   end

package "libpcre3-dev" do
     action :install
end

#php_pear "apc" do
#  action :install
#     directives(:shm_size => 128, :enable_cli => 1)
#end
