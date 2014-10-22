
# create attachments dir
directory node[:gearman_dashboard][:tmp_dir] do
	action :create
	recursive true
end

# configures epel and epel-rightscale repos
include_recipe "gearman_dashboard::setup_epel"

# installs and configures httpd; sets up vhost
include_recipe "gearman_dashboard::setup_vhost"

# installs and configures supervisor daemon
include_recipe "gearman_dashboard::install_supervisord"

# create repo dir for both vagrant and production
directory node[:gearman_dashboard][:deploy][:dir] do
	action :create
	recursive true
end

# disable ipv6
file "/etc/modprobe.d/ipv6.conf" do
	content <<-EOH
	alias ipv6 off
	alias net-pf-10 off
	EOH
	owner "root"
	mode 0644
end
