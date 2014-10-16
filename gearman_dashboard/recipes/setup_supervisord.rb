
path = "#{node[:gearman_dashboard][:vhost][:documentroot]}/application/config/supervisord.json"
file path do
	content JSON.generate({
				:username => node[:gearman_dashboard][:supervisord][:username],
				:password => node[:gearman_dashboard][:supervisord][:password],
				:port => node[:gearman_dashboard][:supervisord][:port]
			});
end
