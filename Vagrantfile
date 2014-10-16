# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant::Config.run do |config|
	config.vm.box = "centos64"
	config.vm.box_url = "http://raven-opensource.s3.amazonaws.com/centos64.box"
	config.vm.network :hostonly, "10.45.0.10"

	config.vm.forward_port 8000, 8085

	overrides = JSON.parse(IO.read("overrides.json"))

	config.vm.share_folder "gearman-repo", 
		"/home/webapps/gearman_dashboard", 
		overrides["local_repo_dir"], 
		{ :create => true, :nfs => true }

	config.vm.provision :chef_solo do |chef|
		chef.cookbooks_path = ["./cookbooks"]
		chef.roles_path = "./roles"
		chef.add_role "gearman_dashboard"
		chef.json = overrides
	end
  
end
