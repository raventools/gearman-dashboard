
bash "flush-iptables" do
    code <<-EOH
        iptables -F
    EOH
end
