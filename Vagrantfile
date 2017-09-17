require 'yaml'
require 'fileutils'

domains = {  
  netcat: 'netcat.dev'
}

# Конфигурация файла
config = {
  local: './vagrant/local.yml',
  example: './vagrant/local.yml'
}

FileUtils.cp config[:example], config[:local] unless File.exist?(config[:local])
# read config
options = YAML.load_file config[:local]


# All Vagrant configuration is done below. The "2" in Vagrant.configure
# configures the configuration version (we support older styles for
# backwards compatibility). Please don't change it unless you know what
# you're doing.
Vagrant.configure("2") do |config|
  # The most common configuration options are documented and commented below.
  # For a complete reference, please see the online documentation at
  # https://docs.vagrantup.com.

  # Every Vagrant development environment requires a box. You can search for
  # boxes at https://vagrantcloud.com/search.
  config.vm.box = "ubuntu/trusty64"

  # Disable automatic box update checking. If you disable this, then
  # boxes will only be checked for updates when the user runs
  # `vagrant box outdated`. This is not recommended.
  config.vm.box_check_update = options['box_check_update']


  config.vm.provider "virtualbox" do |vb|
    #  Процессоры
    vb.cpus = options['cpus']
    # Память
    vb.memory = options['memory']
    # Название машины
    vb.name = options['machine_name']
  end

  # config.vm.network "forwarded_port", guest: 33306,  host: options['mysql_port']

  config.vm.hostname = options['machine_name']

  config.vm.define options['machine_name']

  config.vm.network "private_network", ip: options['ip']

  config.vm.synced_folder options['netcat'] ,  '/app' , owner: 'vagrant', group: 'vagrant'

  config.vm.synced_folder './vagrant' ,  '/vagrant'

  config.vm.provision 'shell', path: './vagrant/provision.sh'

  # config.vm.provision :hostmanager
  # config.hostmanager.enabled            = true
  # config.hostmanager.manage_host        = true
  # config.hostmanager.ignore_private_ip  = false
  # config.hostmanager.include_offline    = true
  # config.hostmanager.aliases            = domains.values

  #
  # View the documentation for the provider you are using for more
  # information on available options.

  # Enable provisioning with a shell script. Additional provisioners such as
  # Puppet, Chef, Ansible, Salt, and Docker are also available. Please see the
  # documentation for more information about their specific syntax and use.
  # config.vm.provision "shell", inline: <<-SHELL
  #   apt-get update
  #   apt-get install -y apache2
  # SHELL
end
