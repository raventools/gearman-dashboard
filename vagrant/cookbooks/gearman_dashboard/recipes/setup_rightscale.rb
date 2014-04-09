
path = "#{node[:gearman_dashboard][:vhost][:documentroot]}/application/config/rightscale.json"
file path do
	content JSON.generate(node[:gearman_dashboard][:rightscale]);
end
