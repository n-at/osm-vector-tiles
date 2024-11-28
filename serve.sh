#!/bin/bash

cd serve

mkdir -m 0777 nginx_cache nginx_logs

git clone "https://github.com/openmaptiles/fonts" fonts

mv ../prepare/cyprus-latest.pmtiles tiles/
# mv ../prepare/russia-latest.pmtiles tiles/

cd static
npm install
