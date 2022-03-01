
#!/bin/bash
#
# Prepare to deploy files by zip
# Run on docker workspace
#

###---------------------------------------###
### settings
###---------------------------------------###
IS_NPM_BUILD=true
IS_STORYBOOK_BUILD=true
LARADOCK_DIR=3sportal_staging_ld/
LARAVEL_DIR=laravel/

###---------------------------------------###
### functions
###---------------------------------------###

##
# Check require params
#
checkRequireParams () {
  echo "checkRequireParams..."
  if [ -z $1 ]; then
    echo "Required ENV(production or development ...)"
    exit;
  else
    ENV=$1
  fi
}

##
# Up docker
#
upDocker () {
  echo "upDocker..."
  cd ${abspath}
  cd ${LARADOCK_DIR}
  docker network create shared
  docker-compose up -d
}

##
# composer install
#
installComposer () {
  echo "installComposer..."
  cd ${abspath}
  cd ${LARADOCK_DIR}
  
  docker-compose exec -T workspace composer install
  # docker-compose exec -T workspace php artisan storage:link
  docker-compose exec -T workspace php artisan cache:clear
  docker-compose exec -T workspace php artisan config:clear
  # docker-compose exec -T workspace php artisan config:cache
}

##
# npm install
#
installNpm () {
  echo "installNpm..."
  cd ${abspath}
  cd ${LARAVEL_DIR}
  npm install
  if [ ${ENV} == "staging-prodserver" ]; then
    npm run development
  else
    npm run ${ENV}
  fi


}

##
# storybook
#
installStoryBook () {
  echo "installStoryBook..."
  cd ${abspath}
  cd ${LARAVEL_DIR}
  npm run build-storybook
  mv storybook-static/ public/
}

##
# replace docker env
#
replaceDockerEnv () {
  echo "replaceDockerEnv..."
  cd ${abspath}
  mv 3sportal_ld ${LARADOCK_DIR}
  cd ${LARADOCK_DIR}
  cp .env .env.tmp
  cp .env.${ENV} .env
}

##
# replace laravel env
#
replaceLaravelEnv () {
  echo "replaceLaravelEnv..."
  cd ${abspath}
  cd ${LARAVEL_DIR}
  cp .env .env.tmp
  cp .env.${ENV} .env
}

##
# compress
#
compressDeployFiles() {
  echo "compressDeployFiles..."
  cd ${abspath}
  if [ $ENV = 'production' ]; then
    echo "production commpress"
    zip -r -q ./deploy.zip -r * .[^.]*
  else
    tar -zcvf deploy.tar.gz * .[^.]*
  fi
}

##
# roll back docker settings
#
rollBackDockerSettings() {
  echo "rollBackDockerSettings..."
  cd ${abspath}
  cd ${LARADOCK_DIR}
  mv -f .env.tmp .env
}

##
# roll back laravel settings
#
rollBackLaravelSettings() {
  echo "rollBackLaravelSettings..."
  cd ${abspath}
  cd ${LARAVEL_DIR}
  mv -f .env.tmp .env
}

###---------------------------------------###
### exec
###---------------------------------------###

abspath=$(cd $(dirname $0); pwd)

# Param $1 from console
checkRequireParams $1

# Replace docker env
replaceDockerEnv

## Replace laravel env
replaceLaravelEnv

# Up docker
upDocker

# Install composer
installComposer

# Install npm
installNpm

## compress files
compressDeployFiles

## Roll back laravel settings
rollBackLaravelSettings

exit;


