
path = "#{node[:gearman_dashboard][:vhost][:documentroot]}/application/config/rightscale.json"
file path do
	content JSON.generate({
				:account_id => node[:gearman_dashboard][:rightscale][:account_id],
				:username => node[:gearman_dashboard][:rightscale][:username],
				:password => node[:gearman_dashboard][:rightscale][:password]
			});
end
