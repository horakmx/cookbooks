mysql_service 'mysql' do
  port "#{node['sandbox']['mysql_port']}"
  version "#{node['sandbox']['mysql_version']}"
  initial_root_password "#{node['sandbox']['mysql_password']}"
#  socket  "/var/run/mysqld/mysqld.sock"
  action [:create, :start]
end
