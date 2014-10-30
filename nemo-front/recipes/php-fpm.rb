include_recipe "php-fpm"

php_fpm_pool "#{node['nemo']['user']}" do
    user "#{node['nemo']['user']}"
    group "#{node['nemo']['group']}"
    listen "/var/run/fpm-#{node['nemo']['user']}.socket"
    listen_owner "#{node['nemo']['user']}"
	listen_group "#{node['nemo']['group']}"
	listen_mode  "0666"
	process_manager "dynamic"
    max_requests 5000
	php_options 'php_admin_flag[log_errors]' => 'on', 'php_admin_value[memory_limit]' => '128M'
end


