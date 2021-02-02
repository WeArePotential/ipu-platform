# Deployment

## Hosting

Hosted at platform.sh

This follows the standard workflow for platform.sh - see https://docs.platform.sh/overview/build-deploy.html

The repository contains versions of
* .platform.app.yaml to control what happens when we push to platform.sh
* .platform/routes.yaml controls the urls
* .platform/services controls php and mysql versions

We keep a copy of the repository on Github, but most work is done from the platform repository.

## Workflow

Make platform.sh your main (origin) repository

* Create a new branch from master.
* Name it depending on the work package, e.g. for work package 99,
wp99-friendly-name
* Work on this branch locally, commit regularly
* To test on Platform
  * checkout develop branch from Platform
  * merge your new branch into develop
  * Push to develop on Platform

Pushing to Platform.sh will rebuild the site and make live for develop, stage and master

Check platform.sh logs for any errors.

* To move to staging
  * check out staging
  * optionally copy live db to staging on the platform.sh website
  * merge branch and push.

* To move to Live, check out master, merge branch and push.

Github
* Once tested and signed-off, push branches to github
* Can push branch to github to share with other developers at any point


### Patching
Add patches (for contrib modules) to the patches folder and add into composer.json.
They will automatically be integrated on push to platform.
