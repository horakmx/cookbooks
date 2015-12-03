remote_directory "#{node['webpagetest']['root_directory']}" do
  source 'wpt_www'
  owner node['webpagetest']['system_user']
  group node['webpagetest']['system_group']
  mode '0755'
  action :create
end
