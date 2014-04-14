


package "uuid"
package "libuuid"
package "libevent"
package "gperf"
package "boost"


{
	"gearman" => "gearman-1.1.1-1.x86_64.rpm",
	"php53u-pecl-gearman" => "php53u-pecl-gearman-1.1.2-1.x86_64.rpm",
	"libmemcached" => "libmemcached-1.0.16-1.el6.art.x86_64.rpm"
}.each do |n,r|
	tmp_path = "#{node[:gearman_dashboard][:tmp_dir]}/#{r}"
	remote_file tmp_path do
		source "#{node[:gearman_dashboard][:attachment_url]}/#{r}"
	end

	rpm_package n do
		source tmp_path
		notifies :run, "bash[run-ldconfig]", :immediately
	end
end

# necessary after installing libgearman above
bash "run-ldconfig" do
	code <<-EOH
		ldconfig
	EOH
	action :nothing
	supports [:run]
end

user "gearman" do
	system true
	shell "/bin/false"
	action :create
end

gearmand_wdir = "/var/run/gearman"
directory gearmand_wdir do
	action :create
	user "gearman"
	mode 0755
end

gearmand_command = "/usr/sbin/gearmand --pid-file=#{gearmand_wdir}/gearmand.pid --log-file=stderr --round-robin --job-retries=1"
template "/etc/supervisor.d/gearmand.conf" do
	source "supervisor_program.conf.erb"
	variables ({
			:name => "gearmand",
			:command => gearmand_command,
			:directory => gearmand_wdir,
			:user => "gearman",
			:numprocs => 1
			})
	notifies :restart, "service[supervisord]", :delayed
end

worker_command = "php index.php gearmanworker work"
worker_wdir = "#{node[:gearman_dashboard][:vhost][:documentroot]}"
template "/etc/supervisor.d/gearman_worker.conf" do
	source "supervisor_program.conf.erb"
	variables ({
			:name => "gearman_worker",
			:command => worker_command,
			:numprocs => 4,
			:directory => worker_wdir,
			:user => "apache"
			})
	notifies :restart, "service[supervisord]", :delayed
end

