#!/bin/bash
now="$(date +'%d/%m/%Y/%r')";
git add .;
git commit -m "DEPLOY LIVE AT: $now";
git push origin master;
echo "pushed";
curl https://forge.laravel.com/servers/144475/sites/368191/deploy/http?token=dDjvLzLkbXcieT9dLRUuxFLHxy9rhMfgGLWMCc8Y

