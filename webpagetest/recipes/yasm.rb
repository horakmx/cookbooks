
#updating packages before installing memcache modules otherwise it fails
#bash "update packages" do
#user "root"
#code <<-EOF
# apt-get update --fix-missing
# EOF
#end

node.default['yasm']['install_method'] = ':source';
include_recipe "yasm"
