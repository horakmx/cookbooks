chef_gem "chef-rewind"
require 'chef/rewind'

include_recipe "varnish"

#rewind :template =>"#{node[:varnish][:dir]}/default.vcl" do
#    source "varnish.default.vcl.erb"
#	cookbook_name "nemo_front"
#end

begin
  r = resources(:template => "#{node[:varnish][:dir]}/default.vcl")
    r.cookbook "nemo-front"
	rescue Chef::Exceptions::ResourceNotFound
	Chef::Log.warn "could not find template to override!"
end

begin
  r = resources(:template => "#{node[:varnish][:default]}")
    r.cookbook "nemo-front"
	rescue Chef::Exceptions::ResourceNotFound
	Chef::Log.warn "could not find template to override!"
end

#template "#{node[:varnish][:dir]}/default.vcl" do
#  source "varnish.default.vcl.erb"
#  owner "root"
#  group "root"
#  mode 0644
#end

#template "#{node[:varnish][:default]}" do
#  source "varnish.default.erb"
#  owner "root"
#  group "root"
#  mode 0644
#end

service "varnish" do
  supports :restart => true, :reload => true
  action [ :enable, :start ]
end

service "varnishlog" do
  supports :restart => true, :reload => true
  action [ :enable, :start ]
end
