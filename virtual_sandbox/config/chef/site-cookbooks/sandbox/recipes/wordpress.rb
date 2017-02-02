package "libmysqlclient-dev" do
action :install
end

mysql2_chef_gem 'default' do
    gem_version "0.4.5" 
    client_version "5.7.16"
    action :install
end

wordpress_latest = Chef::Config[:file_cache_path] + "/wordpress-latest.tar.gz"

mysql_database node['sandbox']['wordpress_mysql_database'] do
  connection ({:host => node['sandbox']['mysql_host'], :username => 'root', :password => node['sandbox']['mysql_password'], :socket => "/run/mysql-mysql/mysqld.sock"})
  action :create
end

mysql_database_user node['sandbox']['wordpress_mysql_username'] do
  connection ({:host => node['sandbox']['mysql_host'], :username => 'root', :password => node['sandbox']['mysql_password'], :socket => "/run/mysql-mysql/mysqld.sock"})
  password node['sandbox']['wordpress_mysql_password']
  database_name node['sandbox']['wordpress_mysql_database']
  privileges [:select,:update,:insert,:create,:delete]
  action :grant
end

remote_file wordpress_latest do
  source "http://wordpress.org/latest.tar.gz"
  mode "0644"
end

directory node['sandbox']['wordpress_dir'] do
  owner "root"
  group "root"
  mode "0755"
  action :create
  recursive true
end

execute "untar-wordpress" do
  cwd node['sandbox']['wordpress_dir']
  command "tar --strip-components 1 -xzf " + wordpress_latest
  creates node['sandbox']['wordpress_dir'] + "/wp-settings.php"
end
