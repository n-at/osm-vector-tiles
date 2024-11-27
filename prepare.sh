#!/bin/bash

mkdir -m 0777 prepare

cd prepare

wget "https://raw.githubusercontent.com/systemed/tilemaker/refs/heads/master/get-coastline.sh"
chmod +x get-coastline.sh
#remove curl --proto '=https' --tlsv1.3
./get-coastline.sh

wget "https://raw.githubusercontent.com/systemed/tilemaker/refs/heads/master/get-landcover.sh"
chmod +x get-landcover.sh
#remove curl --proto '=https' --tlsv1.3
./get-landcover.sh

wget -O config.json "https://raw.githubusercontent.com/systemed/tilemaker/refs/heads/master/resources/config-openmaptiles.json"
wget -O process.lua "https://raw.githubusercontent.com/systemed/tilemaker/refs/heads/master/resources/process-openmaptiles.lua"

mkdir -m 0777 store

#download .osm.pbf
wget "https://download.geofabrik.de/europe/cyprus-latest.osm.pbf"
# wget "https://download.geofabrik.de/russia-latest.osm.pbf"
