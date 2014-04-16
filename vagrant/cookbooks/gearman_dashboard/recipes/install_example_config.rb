
config_path = "#{node[:gearman_dashboard][:vhost][:documentroot]}/application/config"

["masters.json","arrays.json","instances.json"].each do |f|
	dest = "#{config_path}/#{f}"
	remote_file dest do
		source "file://#{dest}.example"
		action :create
	end
end
