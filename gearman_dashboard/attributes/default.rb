# vhost
default[:gearman_dashboard][:vhost][:name] = "gearmandashboard"
default[:gearman_dashboard][:vhost][:servername] = "gearmandashboard.site"
default[:gearman_dashboard][:vhost][:serveraliases] = []
default[:gearman_dashboard][:vhost][:documentroot] = "/home/webapps/gearman_dashboard"

# rightscale
default[:gearman_dashboard][:rightscale][:account_id] = "0000"
default[:gearman_dashboard][:rightscale][:username] = "bogus@username.com"
default[:gearman_dashboard][:rightscale][:password] = "12345"

# supervisord
default[:gearman_dashboard][:supervisord][:username] = "super"
default[:gearman_dashboard][:supervisord][:password] = "12345"
default[:gearman_dashboard][:supervisord][:port] = "9110"

# deploy
default[:gearman_dashboard][:deploy][:repo] = "git@github.com:raventools/gearman-dashboard.git"
default[:gearman_dashboard][:deploy][:branch] = "master"
default[:gearman_dashboard][:deploy][:key] = ""

# php config tuning
default[:gearman_dashboard][:php_conf] = [
	"log_errors = On",
	"error_log = /var/log/httpd/php_error_log",
	"date.timezone = US/Eastern"
	]

# attachments
default[:gearman_dashboard][:tmp_dir] = "/tmp/attachments"
default[:gearman_dashboard][:attachment_url] = "http://raven-opensource.s3.amazonaws.com"
