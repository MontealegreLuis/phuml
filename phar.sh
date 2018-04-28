#!/bin/bash

# Unpack secrets; -C ensures they unpack *in* the .travis directory
tar xvf .travis/secrets.tar -C .

# Setup SSH agent:
eval "$(ssh-agent -s)" #start the ssh agent
chmod 600 .travis/build.pem
ssh-add .travis/build.pem

# Setup git defaults:
git config --global user.email "montealegreluis@gmail.com"
git config --global user.name "phUML PHAR file deployment"

# Add SSH-based remote to GitHub repo:
git remote add deploy git@github.com:MontealegreLuis/phuml.git
git fetch deploy

# Get box and build PHAR
curl -LSs https://box-project.github.io/box2/installer.php | php
./box.phar build -vv

# Without the following step, we cannot checkout the gh-pages branch due to
# file conflicts:
mv phuml.phar phuml.phar.tmp

# Checkout gh-pages and add PHAR file and version:
git checkout -b gh-pages deploy/gh-pages
mv phuml.phar.tmp phuml.phar
sha1sum phuml.phar > phuml.phar.version
git add phuml.phar phuml.phar.version

# Commit and push:
git commit -m 'Update phUML phar file with latest version'
git push deploy gh-pages:gh-pages
