include_recipe "php-fpm"

php_fpm_pool "#{node['crd-api']['system_user']}" do
    user "#{node['crd-api']['system_user']}"
    group "#{node['crd-api']['system_group']}"
    listen "/var/run/fpm-#{node['crd-api']['system_user']}.socket"
    listen_owner "#{node['crd-api']['system_user']}"
	listen_group "#{node['crd-api']['system_group']}"
	listen_mode  "0666"
	process_manager "dynamic"
    max_requests 5000
	php_options 'php_admin_flag[log_errors]' 						=> 'on',
				'php_admin_value[memory_limit]' 					=> '256M',
				'php_admin_value[max_execution_time]' 				=> "60",
				'php_admin_value[session.gc_maxlifetime]' 			=> "1440",
				'php_admin_value[open_basedir]' 					=> "#{node['crd-api']['base_directory']}:/usr/share/php:/usr/share/locale:/mnt/share:/home/common:/tmp",
				'php_admin_value[upload_tmp_dir]' 					=> "#{node['crd-api']['root_directory']}/tmp",
				'php_admin_value[display_errors]' 					=> "1",
				'php_admin_value[upload_max_filesize]' 				=> "10M",
				'php_admin_value[post_max_size]' 					=> "10M"
end 

service "php5-fpm" do
  notifies :restart, "service[php5-fpm]", :delayed
end
