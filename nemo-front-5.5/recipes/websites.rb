include_recipe 'git'
include_recipe 'vim'


bash "checkout_sites" do
user "#{node['nemo']['system_user']}"
group "#{node['nemo']['system_group']}"

code <<-EOF
cd #{node['nemo']['root_directory']};

if [ ! -d "fo-currys" ]; then
echo "Checkouting currys...";
git clone -b "#{node['nemo']['git_site_branch']}" "#{node['nemo']['git_site_repo']}" fo-currys;
echo "Done";
echo "Checkouting config..."
git clone -b "#{node['nemo']['git_config_branch']}" "#{node['nemo']['git_config_repo']}" configuration;
echo "Done"
echo "Checkouting common.."
git clone -b "#{node['nemo']['git_common_branch']}" "#{node['nemo']['git_common_repo']}" fo-currys/core/common/;
echo "Done.";
echo "Install dependencies...";
cd configuration;
composer install;
echo "Done.";
echo "Edit configuration...";
cp personal_config/configuration.yml.dist personal_config/configuration.yml;
cp personal_config/paths.yml.dist personal_config/paths.yml
perl -0777 -i -pe "s/doej01/common/sg" personal_config/configuration.yml;
#perl -0777 -i -pe "s/\\/Users\\/doej01\\/Projects\\/dixons-carphone\\//#{node['nemo']['root_directory']}\\/web_dir\\//"
perl -0777 -i -pe "s/\\/Users\\/doej01\\/Projects\\/dixons-carphone\\//\\/vagrant\\/www\\//sg" personal_config/paths.yml
perl -0777 -i -pe "s/doej01/common/sg" personal_config/paths.yml;
echo "Done";
echo "Creating Config";
php config.php fo-currys dev-common
echo "Done";
#perl -0777 -i -pe "s/fo\\\\\\.dev\\\\\\.hml\\\\\\.dixonsretail\\\\\\.net/local(:[0-9]*)?/" fo-currys/include/networking.inc.php;
#perl -0777 -i -pe "s/(define\\('INTERNAL_IP_MASK',).*?\\);/\\1 '127\\\\\\.0\\\\\\.0\\\\\\.1;10\\\\\\.0\\\\\\.2\\\\\\.2');/sg" fo-currys/include/conf-currys/dev/server.conf.php;
#perl -0777 -i -pe "s/(define\\('DEBUG_IP_MASK',).*?\\);/\\1 '127\\\\\\.0\\\\\\.0\\\\\\.1;10\\\\\\.0\\\\\\.2\\\\\\.2');/sg" fo-currys/include/conf-currys/dev/server.conf.php;
#perl -0777 -i -pe "s/('https?:\\/\\/)'\\s*\\.\\s*getenv\\(\\s*'EM_USERNAME'\\s*\\)\\s*\\.\\s*'/\\1common/sg" fo-currys/include/conf-currys/dev/server.conf.php
fi

cd #{node['nemo']['root_directory']};
if [ ! -h "fo-pcw" ]; then
ln -s fo-currys fo-pcw
fi

#Creating includes folder for memcache config file
if [ ! -d "includes" ]; then
	mkdir includes
fi

#chown #{node['nemo']['system_user']}:#{node['nemo']['system_group']} #{node['nemo']['root_directory']}

EOF
end

cookbook_file "couchbase.inc.php" do
path "#{node['nemo']['root_directory']}/includes/couchbase.inc.php"
action :create_if_missing
end

cookbook_file "couchbase-data.inc.php" do
path "#{node['nemo']['root_directory']}/includes/couchbase-data.inc.php"
action :create_if_missing
end

cookbook_file "couchbase-sessions.inc.php" do
path "#{node['nemo']['root_directory']}/includes/couchbase-sessions.inc.php"
action :create_if_missing
end


cookbook_file "hosts" do
path "/etc/hosts"
action :create
end

