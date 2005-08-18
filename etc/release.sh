#!/bin/bash
# $Id: release.sh,v 1.15 2005/06/24 00:38:54 demian Exp $

# +---------------------------------------------------------------------------+
# | script for automating a Seagull release                                   |
# +---------------------------------------------------------------------------+
# | execute from seagull svn repository root                                  |
# +---------------------------------------------------------------------------+
##############################
# init vars + binaries
##############################

# binaries
SVN=/usr/bin/svn
SCP=/usr/bin/scp
FTP=/usr/bin/ftp

# SF FTP details
FTP_HOSTNAME=upload.sourceforge.net
FTP_USERNAME=anonymous
FTP_PASSWORD=demian@phpkitchen.com
FTP_REMOTE_DIR=incoming

# Get the tag name from the command line:
REVISION_NUM=$1
RELEASE_NAME=$2
PROJECT_NAME=seagull
SVN_REPO_LEAF_FOLDER_NAME=0.4-bugfix
SVN_REPO_URL=http://seagull.phpkitchen.com:8172/svn/seagull/branches/$SVN_REPO_LEAF_FOLDER_NAME

SVN_REPO_TAGS_URL=http://seagull.phpkitchen.com:8172/svn/seagull/tags


##############################
# usage
##############################
function usage()
{
      echo ""
      echo "Usage: ./release.sh revision_num release_name"
      echo "    where \"revision_num\" is the $PROJECT_NAME svn revision number (e.g. 226)"
      echo "    and \"release_name\" is the release name (e.g. 0.4.5) which gives the full name \"seagull-0.4.5\""
}

##############################
# check args
##############################
function checkArgs()
{
    # Check that arguments were specified:
    if [ -z $REVISION_NUM ] || [ -z $RELEASE_NAME ]; then
      usage
      exit 1
    fi
}

##############################
# check previous versions
##############################
function checkPreviousVersions()
{
    # Check that the release directory doesn't already exist (fresh export):
    if [ -d "/tmp/$SVN_REPO_LEAF_FOLDER_NAME" ]; then
      echo "Removing last $PROJECT_NAME export ..."
      rm -rf /tmp/$SVN_REPO_LEAF_FOLDER_NAME
    fi
    
    # Check that the release directory doesn't already exist:
    if [ -d "/tmp/$PROJECT_NAME-$RELEASE_NAME" ]; then
      echo "Removing last $PROJECT_NAME renamed export ..."
      rm -rf /tmp/$PROJECT_NAME-$RELEASE_NAME
    fi
    
    # Check that the last tarball doesn't exist:
    if [ -e "/tmp/$PROJECT_NAME-$RELEASE_NAME.tar.gz" ]; then
      echo "Removing last $PROJECT_NAME tarball ..."
      rm -f /tmp/$PROJECT_NAME-$RELEASE_NAME.tar.gz
    fi

    # Check that the last apiDocs dir doesn't exist:
    if [ -d "/tmp/seagullApiDocs-$RELEASE_NAME" ]; then
      echo "Removing last seagull apiDocs dir ..."
      rm -rf /tmp/seagullApiDocs-$RELEASE_NAME
    fi
    
    # Check that the last apiDocs tarball doesn't exist:
    if [ -e "/tmp/seagullApiDocs-$RELEASE_NAME.tar.gz" ]; then
      echo "Removing last seagull apiDocs tarball ..."
      rm -f /tmp/seagullApiDocs-$RELEASE_NAME.tar.gz
    fi
}

##############################
# tag release
##############################
function tagRelease()
{
    # tag release
    $SVN copy $SVN_REPO_URL $SVN_REPO_TAGS_URL/$RELEASE_NAME
}

##############################
# export svn and package
##############################
function exportSvnAndPackage()
{   
    # export release
    $SVN export $SVN_REPO_URL -r $REVISION_NUM 
    
    #rename trunk to project name
    mv $SVN_REPO_LEAF_FOLDER_NAME $PROJECT_NAME
    
    # remove unwanted dirs
    rm -f $PROJECT_NAME/etc/badBoyWebTests.bb
    rm -f $PROJECT_NAME/etc/cvsNightlyBuild.sh
    rm -f $PROJECT_NAME/etc/demoReload.sh
    rm -f $PROJECT_NAME/etc/generatePackage.php
    rm -f $PROJECT_NAME/etc/phpDocWeb.ini
    rm -f $PROJECT_NAME/etc/release.sh
    rm -rf $PROJECT_NAME/lib/other/phpthumb
    rm -rf $PROJECT_NAME/lib/pear/Spreadsheet
    rm -rf $PROJECT_NAME/lib/SGL/tests       
    rm -rf $PROJECT_NAME/modules/cart
    rm -rf $PROJECT_NAME/modules/rate
    rm -rf $PROJECT_NAME/modules/shop
    rm -rf $PROJECT_NAME/modules/user/tests
    rm -f $PROJECT_NAME/www/errorTests.php     
    rm -rf $PROJECT_NAME/www/images/shop
    rm -rf $PROJECT_NAME/www/themes/default/cart
    rm -rf $PROJECT_NAME/www/themes/default/rate
    rm -rf $PROJECT_NAME/www/themes/default/shop
    
    # rename folder to current release
    mv $PROJECT_NAME $PROJECT_NAME-$RELEASE_NAME
    
    # tar and zip
    tar cvf $PROJECT_NAME-$RELEASE_NAME.tar $PROJECT_NAME-$RELEASE_NAME
    gzip -f $PROJECT_NAME-$RELEASE_NAME.tar
}

##############################
# upload whole package release to SF
##############################
function uploadToSfWholePackage()
{
    # ftp upload to SF
    
    $FTP -nd $FTP_HOSTNAME <<EOF
user $FTP_USERNAME $FTP_PASSWORD
bin
has
cd $FTP_REMOTE_DIR
put $PROJECT_NAME-$RELEASE_NAME.tar.gz
bye
EOF
}

##############################
# documentation generation
##############################
function generateApiDocs()
{
    #make apiDocs script executable
    chmod 755 $PROJECT_NAME-$RELEASE_NAME/etc/phpDocCli.sh
    
    #execute phpDoc
    $PROJECT_NAME-$RELEASE_NAME/etc/phpDocCli.sh

    # rename folder    
    mv seagullApiDocs seagullApiDocs-$RELEASE_NAME
}

##############################
# documentation packaging
##############################
function packageApiDocs()
{
    tar cvf seagullApiDocs-$RELEASE_NAME.tar seagullApiDocs-$RELEASE_NAME
    gzip -f seagullApiDocs-$RELEASE_NAME.tar
}

##############################
# upload Api docs
##############################
function uploadToSfApiDocs()
{
    # ftp upload to SF
    
    $FTP -nd $FTP_HOSTNAME <<EOF
user $FTP_USERNAME $FTP_PASSWORD
bin
has
cd $FTP_REMOTE_DIR
put seagullApiDocs-$RELEASE_NAME.tar.gz
bye
EOF
}

##############################
# scp changelog to sgl site
##############################
function scpChangelogToSglSite()
{
    scp $PROJECT_NAME-$RELEASE_NAME/CHANGELOG.txt demian@phpkitchen.com:/var/www/html/seagull/web/
}

##############################
##############################
# main
##############################
##############################

checkArgs

checkPreviousVersions

#tagRelease

# move to tmp dir
cd /tmp

exportSvnAndPackage

uploadToSfWholePackage

generateApiDocs

packageApiDocs

uploadToSfApiDocs

scpChangelogToSglSite

exit 0
