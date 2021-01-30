# Deployment

## Hosting

Hosted at platform.sh

We keep a copy of the repository on Github, but most work is done from the platform repository.

## Workflow

* Create a new branch from master.
* Name it depending on the work package, e.g. for work package 99,
wp99-friendly-name
* Work on this branch locally, commit regularly
* Push branch to github to share with other developers
* To test on Platform
  * checkout develop branch from Platform
  * merge your new branch into develop
  * Push to develop on Platform

Pushing to Platform.sh will rebuild the site and make live. Check platform.sh logs for any errors.

* To move to staging
  * check out staging
  * optionally copy live db to staging on the platform.sh website
  * merge branch and push.

* To move to Live, check out master, merge branch and push.

Once tested and signed-off, push branches to github

### Patching
Add patches (for contrib modules) to the patches folder and add into composer.json.
They will automatically be integrated on push to platform.
