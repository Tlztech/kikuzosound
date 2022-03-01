#!/bin/bash

#
#  !!!!! need run in your local. not workspace on docker
#

#path to key file
if [ -z $1 ]; then
  SSH_KEY=
else
  SSH_KEY="-i $1"
fi

echo $SSH_KEY

####################################
# depends params
####################################

# dir
DEPLOY_DIR=/home/projects/stg-3sportal/

# SSH_USER
SSH_USER=projects@stg.3sp.sys4tr.com

# laradock dir
LARADOCK_DIR=3sportal_staging_ld

# root dir
ROOT_DIR=laravel

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
cmds+=("mv 3sportal_ld ${LARADOCK_DIR}")
cmds+=("cp -rp ${LARADOCK_DIR}/ backup_${DATETIME};")

## exec
ssh ${SSH_KEY} ${SSH_USER} -o "StrictHostKeyChecking no" ${cmds[*]}


####################################
# upload
####################################

# upload
/usr/bin/scp ${SSH_KEY} ${TAR_FILE} ${SSH_USER}:${DEPLOY_DIR}


####################################
# stop docker
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

# unzip
cmds+=("echo unzip...;")
cmds+=("sudo rm -rf ${TMP_DIR}/;")
cmds+=("mkdir ${TMP_DIR}/;")
cmds+=("tar -zxf ${TAR_FILE} -C ${TMP_DIR}/;")

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
# cmds+=("sudo docker-compose exec -T workspace php artisan storage:link;")
cmds+=("sudo docker-compose exec -T workspace php artisan config:cache;")
cmds+=("sudo docker-compose exec -T workspace php artisan cache:clear")

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
cmds+=("sudo cp -rp backup_${DATETIME}/laravel/public/tf_model laravel/public/;")
ssh ${SSH_KEY} ${SSH_USER} -o "StrictHostKeyChecking no" ${cmds[*]}


####################################
# permissions
####################################
cmds=()
cmds+=("cd ${DEPLOY_DIR};")

cmds+=("chmod -R 777 ./laravel/storage;")
cmds+=("chmod -R 777 ./laravel/public;")

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
