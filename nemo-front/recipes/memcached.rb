include_recipe "memcached"

service "memcached" do
  enabled true
  running true
  supports :status => true, :restart => true
  action [:enable, :start]
end

template  "/etc/memcached.conf" do
  source "memcached.conf.erb"
  mode 0644
  owner "root"
  group "root"
  notifies :restart, resources(:service => "memcached")
end
