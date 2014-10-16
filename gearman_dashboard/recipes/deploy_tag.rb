
key_path = "/tmp/dashboard_deploy.key"
file key_path do
	content node[:gearman_dashboard][:deploy][:key]
	action :create
	owner "root"
	mode 0600
end

wrapper_path = "/tmp/ssh_wrapper.sh"
template wrapper_path do
	user "root"
	source "ssh_wrapper.sh.erb"
	mode 0700
	variables ({
			:key_path => key_path
			})
end

package "git"

git node[:gearman_dashboard][:vhost][:documentroot] do
	repository node[:gearman_dashboard][:deploy][:repo]
	reference node[:gearman_dashboard][:deploy][:branch]
	action :sync
	ssh_wrapper wrapper_path
end

include_recipe "gearman_dashboard::setup_rightscale"
include_recipe "gearman_dashboard::setup_supervisord"
