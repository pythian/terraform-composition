#!/bin/sh
set -e
service ssh start

# Start Apache in the foreground
apache2ctl -D FOREGROUND
