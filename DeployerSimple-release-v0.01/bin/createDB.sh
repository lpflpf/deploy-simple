#!/usr/bin/env bash
projectPath=$(cd "$(dirname "$0")"; pwd)/../
sqlite3 $projectPath/storage/deploy.db < $projectPath/bin/init.sql
