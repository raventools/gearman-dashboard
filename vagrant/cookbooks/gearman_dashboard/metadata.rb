name             'gearman_dashboard'
maintainer       'YOUR_COMPANY_NAME'
maintainer_email 'YOUR_EMAIL'
license          'All rights reserved'
description      'Installs/Configures gearman_dashboard'
long_description IO.read(File.join(File.dirname(__FILE__), 'README.md'))
version          '0.1.0'

recipe "gearman_dashboard::default", "includes all needed recipes to bootstrap the dashboard"
recipe "gearman_dashboard::deploy", "deploys/updates application"
recipe "gearman_dashboard::setup_vhost", "installs and configures apache and php"
recipe "gearman_dashboard::setup_php", "installs and configures apache and php"
recipe "gearman_dashboard::setup_epel", "includes redhat and rightscale's epel repositories"
recipe "gearman_dashboard::setup_rightscale", "configures rightscale authentication parameters in app"
recipe "gearman_dashboard::setup_supervisord", "configures supervisord authentication parameters in app"
