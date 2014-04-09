
# configures epel and epel-rightscale repos
include_recipe "gearman_dashboard::setup_epel"

# installs and configures httpd; sets up vhost
include_recipe "gearman_dashboard::setup_vhost"

# create repo dir for both vagrant and production
directory node[:gearman_dashboard][:vhost][:documentroot] do
	action :create
	recursive true
end

file "/etc/modprobe.d/ipv6.conf" do
	content <<-EOH
	alias ipv6 off
	alias net-pf-10 off
	EOH
	owner "root"
	mode 0644
end
