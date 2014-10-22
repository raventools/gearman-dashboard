
bash "flush-iptables" do
    code <<-EOH
        iptables -F
    EOH
end

bash "fix-dns" do
    code <<-EOH
        echo "options single-request-reopen" >> /etc/resolv.conf
    EOH
end

include_recipe "gearman_dashboard::install_gearman"
include_recipe "gearman_dashboard::install_example_config"

release_path = node[:gearman_dashboard][:vhost][:documentroot]
eval(File.read("#{release_path}/deploy/before_symlink.rb"))
