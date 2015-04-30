include_recipe 'subversion'
include_recipe 'vim'


bash "checkout_sites" do
#user "#{node['nemo']['user']}"
user "root"
#group "#{node['nemo']['group']}"

code <<-EOF
cd #{node['nemo']['root_directory']};
if [ ! -d "web_dir" ]; then
	mkdir web_dir
fi

echo  "svn co --non-interactive --trust-server-cert --username=#{node['nemo']['svn_user']} --password=#{node['nemo']['svn_password']} #{node['nemo']['svn_currys_branch']} web_dir/fo-currys " > /tmp/svn.log 
echo `pwd` >> /tmp/svn.log
svn co --non-interactive --trust-server-cert --username="#{node['nemo']['svn_user']}" --password="#{node['nemo']['svn_password']}" "#{node['nemo']['svn_currys_branch']}" web_dir/fo-currys 2>&1 >> /tmp/svn2.log;
svn co --non-interactive --trust-server-cert --username="#{node['nemo']['svn_user']}" --password="#{node['nemo']['svn_password']}" #{node['nemo']['svn_currys_branch']} /tmp 2>&1 >> /tmp/svn3.log;
perl -0777 -i -pe "s/fo\\\\\\.dev\\\\\\.hml\\\\\\.dixonsretail\\\\\\.net/local(:[0-9]*)?/" web_dir/fo-currys/include/networking.inc.php;
perl -0777 -i -pe "s/(define\\('INTERNAL_IP_MASK',).*?\\);/\\1 '127\\\\\\.0\\\\\\.0\\\\\\.1;10\\\\\\.0\\\\\\.2\\\\\\.2');/sg" web_dir/fo-currys/include/conf-currys/dev/server.conf.php;
perl -0777 -i -pe "s/(define\\('DEBUG_IP_MASK',).*?\\);/\\1 '127\\\\\\.0\\\\\\.0\\\\\\.1;10\\\\\\.0\\\\\\.2\\\\\\.2');/sg" web_dir/fo-currys/include/conf-currys/dev/server.conf.php;
perl -0777 -i -pe "s/('https?:\\/\\/)'\\s*\\.\\s*getenv\\(\\s*'EM_USERNAME'\\s*\\)\\s*\\.\\s*'/\\1common/sg" web_dir/fo-currys/include/conf-currys/dev/server.conf.php

if [ ! -h "web_dir/fo-pcw" ]; then
ln -s fo-currys web_dir/fo-pcw
fi

#Creating includes folder for memcache config file
if [ ! -d "includes" ]; then
	mkdir includes
fi

chown -R #{node['nemo']['user']}:#{node['nemo']['group']} #{node['nemo']['root_directory']}
EOF

end

cookbook_file "memcache.inc.php" do
path "#{node['nemo']['root_directory']}/includes/memcache.inc.php"
action :create_if_missing
end

cookbook_file "hosts" do
path "/etc/hosts"
action :create
end
