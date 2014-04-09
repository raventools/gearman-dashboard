
# configures epel and epel-rightscale repos
include_recipe "gearman_dashboard::setup_epel"

# installs and configures httpd; sets up vhost
include_recipe "gearman_dashboard::setup_vhost"

# create repo dir for both vagrant and production
directory node[:gearman_dashboard][:vhost][:documentroot] do
	action :create
	recursive true
end
