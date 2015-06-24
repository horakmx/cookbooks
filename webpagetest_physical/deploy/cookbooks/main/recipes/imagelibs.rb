package "libjpeg-turbo-progs" do
     action :install
end

bash "install_exiftool" do
user "root"

code <<-EOF
curl -Lo /tmp/Image-ExifTool-9.95.tar.gz "http://www.sno.phy.queensu.ca/~phil/exiftool/Image-ExifTool-9.95.tar.gz" 
cd /tmp
gzip -dc Image-ExifTool-9.95.tar.gz | tar -xf -
cd Image-ExifTool-9.95
perl Makefile.PL
make test
make install
EOF

end

