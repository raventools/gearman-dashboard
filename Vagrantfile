# -*- mode: ruby -*-
# vi: set ft=ruby :

VAGRANTFILE_API_VERSION = "2"

# configure vagrant
Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|

	config.vm.box = "centos64"
	config.vm.box_url = "http://raven-opensource.s3.amazonaws.com/centos64.box"
	config.vm.network :private_network, ip: "10.45.0.10"
	config.vm.network :forwarded_port, guest: 8000, host: 8085

	overrides = JSON.parse(IO.read(File.dirname(__FILE__)+"/overrides.json"))

	config.vm.synced_folder overrides["local_repo_dir"], 
		"/home/webapps/gearman_dashboard/current", 
		type: "nfs"

	config.vm.provision :chef_solo do |chef|
		chef.cookbooks_path = ["."]
		chef.roles_path = "./roles"
		chef.add_role "gearman_dashboard"
		chef.json = overrides
	end

	config.vm.provider :virtualbox do |vb|
		vb.customize ["modifyvm", :id, "--rtcuseutc", "on", "--memory", "2048", "--cpus", "2", "--natdnshostresolver1", "on"]
	end
  
end
