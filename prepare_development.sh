
#!/bin/bash
#
# Prepare to deploy files by zip
# Run on docker workspace
#

###---------------------------------------###
### args help
###---------------------------------------###
function usage {
  cat <<EOM
Usage: $(basename "$0") [OPTION]...
  -h          Display help
  -e VALUE   enviroment. development or staging or staging
  -l VALUE   laradock dir. default "laradock/"
  -v VALUE    A explanation for arg called b
  -p VALUE    docker params. default "--build nginx workspace php-fpm mariadb"
  -n Boolean  npm build
  -s Boolean  storybook build
  -f Boolean  fast deploy (only laravel files = exclude react,vue )
EOM
  exit 2
}

###---------------------------------------###
### args
###---------------------------------------###

# init
ENV=
IS_NPM_BUILD=true
IS_STORYBOOK_BUILD=true
LARADOCK_DIR=3sportal_ld/
LARAVEL_DIR=laravel/
DOCKER_PARAMS=""
IS_FAST_DEPLOY=false

# params
while getopts ":h:e:l:v:p:n:s:f" optKey; do
  case "$optKey" in
    e)
      ENV=${OPTARG}
      ;;
    l)
      LARADOCK_DIR=${OPTARG}
      ;;
    v)
      LARAVEL_DIR=${OPTARG}
      ;;
    p)
      DOCKER_PARAMS=${OPTARG}
      ;;
    n)
      IS_NPM_BUILD=true
      ;;
    s)
      IS_STORYBOOK_BUILD=true
      ;;
    f)
      IS_FAST_DEPLOY=false
      ;;
    '-h'|'--help'|* )
      usage
      ;;
  esac
done

if [ -z ${ENV} ]; then
  echo "Required -e ENV(production or development ...)"
  exit;
fi

echo "========= params ========="
echo "ENV=${ENV}"
echo "IS_NPM_BUILD=${IS_NPM_BUILD}"
echo "IS_STORYBOOK_BUILD=${IS_STORYBOOK_BUILD}"
echo "LARADOCK_DIR=${LARADOCK_DIR}"
echo "LARAVEL_DIR=${LARAVEL_DIR}"
echo "DOCKER_PARAMS=${DOCKER_PARAMS}"
echo "IS_FAST_DEPLOY=${IS_FAST_DEPLOY}"
echo "========= params ========="

###---------------------------------------###
### functions
###---------------------------------------###
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
  if [ ${ENV} == "staging" ]; then
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
# replace maintenace env
#
replaceMaintenanceEnv () {
  cd ${abspath}
  cd maintenance/
  cp .env .env.tmp
  cp .env.${ENV} .env
}


##
# compress laravel only
#
compressDeployFiles() {
  echo "compressDeployFiles..."
  cd ${abspath}
  tar -zcf deploy.tar.gz ${LARAVEL_DIR}
}

##
# compress all files
#
compressAllDeployFiles() {
  echo "compress All Deploy Files..."
  cd ${abspath}
  tar -zcvf deploy.tar.gz * .[^.]*
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

##
# roll back maintenace settings
#
rollBackMaintenanceSettings() {
  cd ${abspath}
  cd maintenance/
  mv -f .env.tmp .env
}

###---------------------------------------###
### exec
###---------------------------------------###

abspath=$(cd $(dirname $0); pwd)

###----------------###
### START             fast exec (only files)
###----------------###

if "${IS_FAST_DEPLOY}"; then
  replaceLaravelEnv
  compressDeployFiles
  rollBackLaravelSettings
  exit
fi

###----------------###
### END               fast exec (only files)
###----------------###


# Replace docker env
replaceDockerEnv

## Replace laravel env
replaceLaravelEnv

## Replace maintenance env
replaceMaintenanceEnv

# Up docker
upDocker

# Install composer
installComposer

# Install npm
if "${IS_NPM_BUILD}"; then
  installNpm
fi

# Build Storybook
if "${IS_STORYBOOK_BUILD}"; then
  installStoryBook
fi

## compress all files if not fast deploy
compressAllDeployFiles

## Roll back laravel settings
rollBackDockerSettings
rollBackLaravelSettings
rollBackMaintenanceSettings

exit;


