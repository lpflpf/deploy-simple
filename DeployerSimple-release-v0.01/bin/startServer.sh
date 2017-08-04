#!/usr/bin/env bash
projectPath=$(cd "$(dirname "$0")"; pwd)/../
cd "$projectPath/public";

nohup php -S 0.0.0.0:9999 $projectPath/external/router.php >/dev/null 2>&1 &
