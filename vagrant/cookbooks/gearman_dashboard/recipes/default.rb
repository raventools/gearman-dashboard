
# configures epel and epel-rightscale repos
include_recipe "gearman_dashboard::setup_epel"

# installs and configures httpd; sets up vhost
include_recipe "gearman_dashboard::setup_vhost"
