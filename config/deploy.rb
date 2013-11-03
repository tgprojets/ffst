set :stages, %w(prod sandbox)
set :default_stage, "sandbox"

require 'capistrano/ext/multistage'

set :application, "frontend"
set :domain,      "tgprojets.fr"
set :domaingit,   "gitlab.tgprojets.fr"
set :repository,  "git@#{domaingit}:tgilbert/ffst.git"
set :scm,         :git
set :keep_releases,  5
set :git_enable_submodules, 1
set :branch,      "master"
set  :deploy_via,             :copy

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain                        # This may be the same as your `Web` server
role  :db,        domain, :primary => true

set :use_sudo,      false

set :rake,           "rake"
set :rails_env,      "production"
set :migrate_target, :latest

before "deploy:restart" do
  ffst.createlink
end

namespace :ffst do
  desc <<-DESC
    Initialisation répertoire
  DESC
  task :setup do
    run "mkdir -p #{shared_path}/web/pdf"
    run "chmod -R 777 #{shared_path}/web/pdf"
  end

  desc <<-DESC
    Lien vers Pdf
  DESC
  task :createlink do
    run "ln -s #{shared_path}/web/pdf #{current_path}/web/"
  end

end
