#!/usr/bin/env bash

# Move this file onto the server (it is only here for source control reasons)
# If the web root is /home/lapis/www place this file in /home/lapis
# This file will delete your www folder so be sure to back it up beforehand

echo 'Deployment Started'
current_dir="$PWD" # Save the starting directory for use later

DEPLOY_PATH=$(date +%Y%m%d-%H-%M) # Current timestamp used for creating timestamped deployment folders

# Check to see if the releases folder already exists or not
if [ ! -d "releases" ]; then
  mkdir releases # Make releases directory if it doesn't exist yet
fi

# Change into the releases directory, create folder with the timestamp as the name then clone the github repo into that folder
cd releases
mkdir $DEPLOY_PATH
cd $DEPLOY_PATH

git clone -b $1 git@github.com:tominovak33/lapis.git #Git clone the specified branch of the repo. (specify as the first command line argument eg: "sh deployment_sctipt.sh master"

cd "$current_dir" # Change back to starting directory

# The config file is server specific and not in the repo so
# This should be set up before the first deploy. Use the example file in the repo to get started)
ln -s $current_dir/includes/config.php releases/$DEPLOY_PATH/lapis/includes/config.php #symlink the config file in the new folder back to the servers config file.

rm -rf www # Delete the current web folder

ln -s releases/$DEPLOY_PATH/lapis www # Symlink the new folder that was just cloned

touch www/logs/query_log.txt

echo 'Deployment Complete'
