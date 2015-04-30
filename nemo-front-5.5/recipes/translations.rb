cookbook_file "gettext.tgz" do
user "#{node['nemo']['user']}"
group "#{node['nemo']['group']}"
path "/tmp/gettext.tgz"
action :create
end

bash "extract_translations" do
user "#{node['nemo']['user']}"
group "#{node['nemo']['group']}"

code <<-EOF
if [ -f "/tmp/gettext.tgz" ]; then
tar -zxvf /tmp/gettext.tgz -C /home/pulvaj01/includes/;
rm -rf /tmp/gettext.tgz
fi

EOF

end


