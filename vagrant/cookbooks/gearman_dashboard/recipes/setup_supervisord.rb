
path = "#{node[:gearman_dashboard][:vhost][:documentroot]}/application/config/supervisord.json"
file path do
	content JSON.generate(node[:gearman_dashboard][:supervisord]);
end
