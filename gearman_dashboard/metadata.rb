name             'gearman_dashboard'
maintainer       'YOUR_COMPANY_NAME'
maintainer_email 'YOUR_EMAIL'
license          'All rights reserved'
description      'Installs/Configures gearman_dashboard'
long_description IO.read(File.join(File.dirname(__FILE__), 'README.md'))
version          '0.1.0'

recipe "gearman_dashboard::default", "includes all needed recipes to bootstrap the dashboard"
recipe "gearman_dashboard::deploy_tag", "deploys/updates application"
recipe "gearman_dashboard::setup_vhost", "installs and configures apache and php"
recipe "gearman_dashboard::setup_php", "installs and configures apache and php"
recipe "gearman_dashboard::setup_epel", "includes redhat and rightscale's epel repositories"
recipe "gearman_dashboard::setup_rightscale", "configures rightscale authentication parameters in app"
recipe "gearman_dashboard::setup_supervisord", "configures supervisord authentication parameters in app"

attribute "gearman_dashboard",
	:display_name => "Gearman Dashboard",
	:type => "hash"

attribute "gearman_dashboard/vhost/name",
    :display_name => "Vhost Name",
    :description => "Vhost Name",
    :required => "recommended",
    :type => "string",
    :recipes => ["gearman_dashboard::default","gearman_dashboard::setup_vhost"]

attribute "gearman_dashboard/vhost/servername",
    :display_name => "Vhost ServerName",
    :description => "Vhost ServerName",
    :required => "recommended",
    :type => "string",
    :recipes => ["gearman_dashboard::default","gearman_dashboard::setup_vhost"]

attribute "gearman_dashboard/vhost/serveraliases",
    :display_name => "Vhost ServerAliases",
    :description => "Vhost ServerAliases",
    :required => "recommended",
    :type => "array",
    :recipes => ["gearman_dashboard::default","gearman_dashboard::setup_vhost"]

attribute "gearman_dashboard/vhost/documentroot",
    :display_name => "Vhost DocRoot",
    :description => "Vhost DocRoot",
    :required => "recommended",
    :type => "string",
    :recipes => ["gearman_dashboard::default","gearman_dashboard::setup_vhost"]

attribute "gearman_dashboard/rightscale/account_id",
    :display_name => "Rightscale API Account ID",
    :description => "Rightscale API Account ID",
    :required => "recommended",
    :type => "string",
    :recipes => ["gearman_dashboard::default","gearman_dashboard::setup_rightscale"]

attribute "gearman_dashboard/rightscale/username",
    :display_name => "Rightscale API Username",
    :description => "Rightscale API Username",
    :required => "recommended",
    :type => "string",
    :recipes => ["gearman_dashboard::default","gearman_dashboard::setup_rightscale"]

attribute "gearman_dashboard/rightscale/password",
    :display_name => "Rightscale API Password",
    :description => "Rightscale API Password",
    :required => "recommended",
    :type => "string",
    :recipes => ["gearman_dashboard::default","gearman_dashboard::setup_rightscale"]

attribute "gearman_dashboard/supervisord/username",
    :display_name => "Supervisord username",
    :description => "Supervisord username",
    :required => "recommended",
    :type => "string",
    :recipes => ["gearman_dashboard::default","gearman_dashboard::setup_supervisord"]

attribute "gearman_dashboard/supervisord/password",
    :display_name => "Supervisord password",
    :description => "Supervisord password",
    :required => "recommended",
    :type => "string",
    :recipes => ["gearman_dashboard::default","gearman_dashboard::setup_supervisord"]

attribute "gearman_dashboard/supervisord/port",
    :display_name => "Supervisord port",
    :description => "Supervisord port",
    :required => "recommended",
    :type => "string",
	:default => "9110",
    :recipes => ["gearman_dashboard::default","gearman_dashboard::setup_supervisord"]

attribute "gearman_dashboard/deploy/repo",
    :display_name => "Git Repository URL",
    :description => "Git Repository URL",
    :required => "recommended",
    :type => "string",
    :recipes => ["gearman_dashboard::default","gearman_dashboard::deploy_tag"]

attribute "gearman_dashboard/deploy/branch",
    :display_name => "Git Branch",
    :description => "Git Branch",
    :required => "recommended",
    :type => "string",
	:default => "master",
    :recipes => ["gearman_dashboard::default","gearman_dashboard::deploy_tag"]

attribute "gearman_dashboard/deploy/key",
    :display_name => "Git Deploy Key",
    :description => "Git Deploy Key",
    :required => "optional",
    :type => "string",
    :recipes => ["gearman_dashboard::default","gearman_dashboard::deploy_tag"]
