package('syslog-ng-core') { action :install }
include_recipe "syslog-ng"

cookbook_file "syslog-ng.conf" do
path "/etc/syslog-ng/syslog-ng.conf"
action :create
end
