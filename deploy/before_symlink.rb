
bash "install composer libs" do
	cwd release_path
	code <<-EOH
	unset GIT_SSH
	php -d allow_url_fopen=On /usr/bin/composer install
	EOH
end

path = "#{release_path}/application/config/supervisord.json"
file path do
	content JSON.generate({
				:username => node[:gearman_dashboard][:supervisord][:username],
				:password => node[:gearman_dashboard][:supervisord][:password],
				:port => node[:gearman_dashboard][:supervisord][:port]
			});
end

path = "#{release_path}/application/config/rightscale.json"
file path do
	content JSON.generate({
				:account_id => node[:gearman_dashboard][:rightscale][:account_id],
				:username => node[:gearman_dashboard][:rightscale][:username],
				:password => node[:gearman_dashboard][:rightscale][:password]
			});
end

