#!/bin/bash

cd serve

mkdir -m 0777 nginx_cache nginx_logs

git clone "https://github.com/openmaptiles/fonts" fonts

git clone "https://github.com/openmaptiles/osm-bright-gl-style"
mv osm-bright-gl-style/icons glyphs
rm -rf osm-bright-gl-style

mv ../prepare/cyprus-latest.pmtiles tiles/
# mv ../prepare/russia-latest.pmtiles tiles/

cd static
npm install
