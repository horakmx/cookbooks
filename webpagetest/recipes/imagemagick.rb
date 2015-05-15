
#updating packages before installing memcache modules otherwise it fails
#bash "update packages" do
#user "root"
#code <<-EOF
# apt-get update --fix-missing
# EOF
#end

include_recipe "imagemagick"
