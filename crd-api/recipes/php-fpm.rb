#updating packages before installing  otherwise it fails
#bash "update packages" do
#user "root"
#code <<-EOF
# apt-get update --fix-missing
#  EOF
#end

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
				'php_admin_value[post_max_size]' 					=> "10M",
				'php_admin_value[xdebug.profiler_output_dir]' 		=> "#{node['crd-api']['root_directory']}/xdebug",
				'php_admin_value[xdebug.remote_port]' 				=> "9000",
				'php_admin_value[xdebug.remote_connect_back]' 		=> "1",
				'php_admin_value[xdebug.remote_enable]' 			=> "1",
				'php_admin_value[xdebug.profiler_enable_trigger]' 	=> "1",
				'php_admin_value[xdebug.collect_assignments]' 		=> "1",
				'php_admin_value[xdebug.collect_includes]' 			=> "1",
				'php_admin_value[xdebug.collect_params]' 			=> "4",
				'php_admin_value[xdebug.collect_return]' 			=> "1",
				'php_admin_value[xdebug.show_mem_delta]' 			=> "1",
				'php_admin_value[xdebug.trace_output_name]' 		=> "trace.%R"
#;php_admin_value[xdebug.trace_enable_trigger] = 1
end 
