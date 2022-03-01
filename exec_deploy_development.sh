#!/bin/bash

#
#  !!!!! need run in your local. not workspace on docker
#

###---------------------------------------###
### prams help
###---------------------------------------###
function usage {
  cat <<EOM
Usage: $(basename "$0") [OPTION]...
  -h         Display help
  -i VALUE   ssh key
  -u VALUE   ssh user. ex) projects@dev.3sp.sys4tr.com
  -d VALUE   project name(project dir). use for dir name ex)"/home/projects/$3/"
  -p VALUE   docker params. default "--build nginx workspace php-fpm mariadb"
  -l VALUE   laradock dir
  -r VALUE   laravel root project root dir
  -f Boolean  fast deploy (only laravel files = exclude react,vue )
EOM
  exit 2
}


####################################
# args
####################################

# init
SSH_KEY=
SSH_USER=
DEPLOY_DIR=
DOCKER_PARAMS=
LARADOCK_DIR=3sportal_ld
ROOT_DIR=laravel
IS_FAST_DEPLOY=false

# params
while getopts ":h:i:u:d:p:l:r:f" optKey; do
  case "$optKey" in
    i)
      SSH_KEY="-i ${OPTARG}"
      ;;
    u)
      SSH_USER=${OPTARG}
      ;;
    d)
      DEPLOY_DIR="/home/projects/${OPTARG}/"
      ;;
    p)
      DOCKER_PARAMS=${OPTARG}
      ;;
    l)
      LARADOCK_DIR=${OPTARG}
      ;;
    r)
      ROOT_DIR=true
      ;;
    f)
      IS_FAST_DEPLOY=false
      ;;
    '-h'|'--help'|* )
      usage
      ;;
  esac
done

if [ -z "${SSH_KEY}" ]; then
  echo "Required -i SSH_KEY ..."
  exit;
fi
if [ -z ${SSH_USER} ]; then
  echo "Required -u SSH_USER ..."
  exit;
fi
if [ -z ${DEPLOY_DIR} ]; then
  echo "Required -d DEPLOY_DIR ..."
  exit;
fi
if [ -z "${DOCKER_PARAMS}" ]; then
  echo "Required -p DOCKER_PARAMS ..."
  exit;
fi

echo "========= params ========="
echo "SSH_KEY=${SSH_KEY}"
echo "SSH_USER=${SSH_USER}"
echo "DEPLOY_DIR=${DEPLOY_DIR}"
echo "DOCKER_PARAMS=${DOCKER_PARAMS}"
echo "LARADOCK_DIR=${LARADOCK_DIR}"
echo "ROOT_DIR=${ROOT_DIR}"
echo "IS_FAST_DEPLOY=${IS_FAST_DEPLOY}"
echo "========= params ========="


####################################
# fixed params
####################################

TAR_FILE=deploy.tar.gz
TMP_DIR=tmp_deploy
DATETIME=`date +%Y-%m-%d_%H.%M.%S`


####################################
# backup
####################################
cmds=()
cmds+=("cd ${DEPLOY_DIR};")


## backup
cmds+=("echo remove old backups...;")
cmds+=("bash ./delete_backup.sh;")

cmds+=("echo backup...;")
cmds+=("mkdir backup_${DATETIME};")
cmds+=("cp -rp ${ROOT_DIR}/ backup_${DATETIME};")
cmds+=("cp -rp ${LARADOCK_DIR}/ backup_${DATETIME};")

## exec
ssh ${SSH_KEY} ${SSH_USER} -o "StrictHostKeyChecking no" ${cmds[*]}


####################################
# upload
####################################

# upload
/usr/bin/scp ${SSH_KEY} ${TAR_FILE} ${SSH_USER}:${DEPLOY_DIR}


####################################
# up zip
####################################

cmds=()
cmds+=("cd ${DEPLOY_DIR};")

# unzip
cmds+=("echo unzip...;")
cmds+=("sudo rm -rf ${TMP_DIR}/;")
cmds+=("mkdir ${TMP_DIR}/;")
cmds+=("tar -zxf ${TAR_FILE} -C ${TMP_DIR}/;")

ssh ${SSH_KEY} ${SSH_USER} -o "StrictHostKeyChecking no" ${cmds[*]}


####################################
# laravel maintenance mode down
####################################
cmds=()
cmds+=("cd ${DEPLOY_DIR};")

cmds+=("echo laravel maintenance mode on ...;")
cmds+=("cd ${LARADOCK_DIR}/;")
cmds+=("sudo docker-compose exec -T workspace php artisan down;")

ssh ${SSH_KEY} ${SSH_USER} -o "StrictHostKeyChecking no" ${cmds[*]}


#----------------------------------------------------------
# Start fast deploy
#----------------------------------------------------------
if "${IS_FAST_DEPLOY}"; then
    
  #### deploy
  cmds=()
  cmds+=("cd ${DEPLOY_DIR};")
  cmds+=("echo deploy...;")
  cmds+=("cp -rpf ${TMP_DIR}/${ROOT_DIR}/* ${ROOT_DIR}/;")
  ssh ${SSH_KEY} ${SSH_USER} -o "StrictHostKeyChecking no" ${cmds[*]}

  #### UP
  cmds=()
  cmds+=("cd ${DEPLOY_DIR};")
  cmds+=("echo up...;")
  cmds+=("cd ${LARADOCK_DIR}/;")
  cmds+=("sudo docker-compose exec -T workspace php artisan cache:clear;")
  cmds+=("sudo docker-compose exec -T workspace php artisan config:clear;")
  cmds+=("sudo docker-compose exec -T workspace php artisan config:cache;")
  cmds+=("sudo docker-compose exec -T workspace php artisan up;")
  ssh ${SSH_KEY} ${SSH_USER} -o "StrictHostKeyChecking no" ${cmds[*]}

  ####################################
  # strage copy from old to new
  ####################################
  cmds=()
  cmds+=("cd ${DEPLOY_DIR};")

  cmds+=("sudo cp -rp backup_${DATETIME}/laravel/storage laravel/;")
  cmds+=("sudo cp -rp backup_${DATETIME}/laravel/public/audio/stetho_sounds laravel/public/audio/;")
  cmds+=("sudo cp -rp backup_${DATETIME}/laravel/public/img/quiz_packs laravel/public/img/;")
  cmds+=("sudo cp -rp backup_${DATETIME}/laravel/public/img/quizzes laravel/public/img/;")
  cmds+=("sudo cp -rp backup_${DATETIME}/laravel/public/img/stetho_sound_images laravel/public/img/;")
  cmds+=("sudo cp -rp backup_${DATETIME}/laravel/public/video/library_videos laravel/public/video/;")
  cmds+=("sudo cp -rp backup_${DATETIME}/laravel/public/img/library_images laravel/public/img/;")
  cmds+=("sudo cp -rp backup_${DATETIME}/laravel/public/tf_model laravel/public/;")
  ssh ${SSH_KEY} ${SSH_USER} -o "StrictHostKeyChecking no" ${cmds[*]}

  ## permission
  cmds=()
  cmds+=("echo chmod...;")
  cmds+=("cd ${DEPLOY_DIR};")
  cmds+=("sudo chmod -R 777 ./laravel/storage;")
  cmds+=("sudo chmod -R 777 ./laravel/public;")
  ssh ${SSH_KEY} ${SSH_USER} -o "StrictHostKeyChecking no" ${cmds[*]}

  # remove
  cmds=()
  cmds+=("cd ${DEPLOY_DIR};")
  cmds+=("echo remove deploy files...;")
  cmds+=("rm ${TAR_FILE};")
  cmds+=("sudo rm -rf ${TMP_DIR}/;")
  ssh ${SSH_KEY} ${SSH_USER} -o "StrictHostKeyChecking no" ${cmds[*]}
  
  cmds=()
  cmds+=("echo END fast deploy.;")
  ssh ${SSH_KEY} ${SSH_USER} -o "StrictHostKeyChecking no" ${cmds[*]}
  
  
  exit
fi


#----------------------------------------------------------
# End   fast deploy
#----------------------------------------------------------

####################################
# maintenance docker up
####################################

cmds=()
cmds+=("cd ${DEPLOY_DIR};")

## up
cmds+=("echo maintenance docker restart...;")
cmds+=("cd ${TMP_DIR}/maintenance/;")
cmds+=("sudo docker-compose up -d;")

ssh ${SSH_KEY} ${SSH_USER} -o "StrictHostKeyChecking no" ${cmds[*]}


####################################
# wait for update proxy
####################################

sleep 1m

####################################
# stop main docker
####################################

cmds=()
cmds+=("cd ${DEPLOY_DIR};")

cmds+=("echo docker down...;")
cmds+=("cd ${LARADOCK_DIR}/;")
cmds+=("sudo docker-compose down;")

ssh ${SSH_KEY} ${SSH_USER} -o "StrictHostKeyChecking no" ${cmds[*]}


####################################
# deploy
####################################

cmds=()
cmds+=("cd ${DEPLOY_DIR};")

## deploy
cmds+=("echo deploy...;")
cmds+=("sudo rm -rf ${ROOT_DIR}/;")
cmds+=("sudo rm -rf ${LARADOCK_DIR}/;")
cmds+=("cp -rp ${TMP_DIR}/${ROOT_DIR}/ ${ROOT_DIR}/;")
cmds+=("cp -rp ${TMP_DIR}/${LARADOCK_DIR}/ ${LARADOCK_DIR}/;")
cmds+=("cp -p ${TMP_DIR}/delete_backup.sh ./delete_backup.sh;")

ssh ${SSH_KEY} ${SSH_USER} -o "StrictHostKeyChecking no" ${cmds[*]}


####################################
# docker start
####################################
cmds=()
cmds+=("cd ${DEPLOY_DIR};")

cmds+=("echo docker restart...;")
cmds+=("cd ${LARADOCK_DIR}/;")
cmds+=("sudo docker-compose up -d;")
cmds+=("sudo docker-compose exec -T workspace php artisan down;")
cmds+=("sudo docker-compose exec -T workspace php artisan cache:clear;")
cmds+=("sudo docker-compose exec -T workspace php artisan config:clear;")
cmds+=("sudo docker-compose exec -T workspace php artisan config:cache;")

ssh ${SSH_KEY} ${SSH_USER} -o "StrictHostKeyChecking no" ${cmds[*]}


####################################
# strage copy from old to new
####################################
cmds=()
cmds+=("cd ${DEPLOY_DIR};")

cmds+=("cp -rp backup_${DATETIME}/laravel/storage laravel/;")
cmds+=("cp -rp backup_${DATETIME}/laravel/public/audio/stetho_sounds laravel/public/audio/;")
cmds+=("cp -rp backup_${DATETIME}/laravel/public/img/quiz_packs laravel/public/img/;")
cmds+=("cp -rp backup_${DATETIME}/laravel/public/img/quizzes laravel/public/img/;")
cmds+=("cp -rp backup_${DATETIME}/laravel/public/img/stetho_sound_images laravel/public/img/;")
cmds+=("cp -rp backup_${DATETIME}/laravel/public/video/library_videos laravel/public/video/;")
cmds+=("cp -rp backup_${DATETIME}/laravel/public/img/library_images laravel/public/img/;")

ssh ${SSH_KEY} ${SSH_USER} -o "StrictHostKeyChecking no" ${cmds[*]}


####################################
# permissions
####################################
cmds=()
cmds+=("cd ${DEPLOY_DIR};")

cmds+=("sudo chmod -R 777 ./laravel/storage;")
cmds+=("sudo chmod -R 777 ./laravel/public;")

ssh ${SSH_KEY} ${SSH_USER} -o "StrictHostKeyChecking no" ${cmds[*]}


####################################
# wait for update proxy
####################################

sleep 1m


####################################
# maintenance down
####################################

cmds=()
cmds+=("cd ${DEPLOY_DIR};")

## up
cmds+=("echo maintenance docker restart...;")
cmds+=("cd ${TMP_DIR}/maintenance/;")
cmds+=("sudo docker-compose down;")

ssh ${SSH_KEY} ${SSH_USER} -o "StrictHostKeyChecking no" ${cmds[*]}

####################################
# laravel maintenance mode up
####################################
cmds=()
cmds+=("cd ${DEPLOY_DIR};")

cmds+=("echo laravel maintenance mode off ...;")
cmds+=("cd ${LARADOCK_DIR}/;")
cmds+=("sudo docker-compose exec -T workspace php artisan up;")

ssh ${SSH_KEY} ${SSH_USER} -o "StrictHostKeyChecking no" ${cmds[*]}


####################################
# remove no needs
####################################

cmds=()
cmds+=("cd ${DEPLOY_DIR};")

## remove no need files
cmds+=("echo remove deploy files...;")
cmds+=("rm ${TAR_FILE};")
cmds+=("sudo rm -rf ${TMP_DIR}/;")
ssh ${SSH_KEY} ${SSH_USER} -o "StrictHostKeyChecking no" ${cmds[*]}
