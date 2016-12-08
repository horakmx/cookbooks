cookbook_file "gettext.tgz" do
user "#{node['nemo']['system_user']}"
group "#{node['nemo']['system_group']}"
path "/tmp/gettext.tgz"
action :create
end

bash "extract_translations" do
user "#{node['nemo']['system_user']}"
group "#{node['nemo']['system_group']}"

code <<-EOF
if [ -f "/tmp/gettext.tgz" ]; then
tar -zxvf /tmp/gettext.tgz -C /#{node['nemo']['root_directory']}/includes/;
rm -rf /tmp/gettext.tgz
fi

EOF

end


