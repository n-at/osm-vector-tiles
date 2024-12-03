# OpenStreetMap Vector Tiles

Пример подготовки векторных карт на основе OpenStreetMap.

Также настройка и подключение OSRM для построения маршрутов.

Также настройка и подключение Nominatim для прямого и обратного геокодирования.

Используется:

+ [onthegomap/planetiler](https://github.com/onthegomap/planetiler) для преобразования osm.pbf в pmtiles (Apache-2.0)
+ [maplibre/martin](https://github.com/maplibre/martin) в качестве сервера (Apache-2.0)
+ [maplibre/maplibre-gl-js](https://github.com/maplibre/maplibre-gl-js) для отображения карты (BSD-3-Clause)
+ [openmaptiles/fonts](https://github.com/openmaptiles/fonts) - шрифты (OFL, Apache-2.0)
+ [openmaptiles/osm-bright-gl-style](https://github.com/openmaptiles/osm-bright-gl-style) - тема и иконки (BSD-3-Clause, CC-BY 4.0)
+ [gravitystorm/openstreetmap-carto](https://github.com/gravitystorm/openstreetmap-carto) - иконки оригинальной темы OSM Carto (CC0)
+ [Project-OSRM/osrm-backend](https://github.com/Project-OSRM/osrm-backend) - построение маршрутов
+ [osm-search/Nominatim](https://github.com/osm-search/Nominatim) - геокодирование

## Подготовка к созданию карты

Скачивание файлов с данными OSM и planetiler.

```bash
docker network create osm-network

mkdir -m 0777 prepare

cd prepare

wget https://github.com/onthegomap/planetiler/releases/latest/download/planetiler.jar

#Кипр (для тестирования)
wget "https://download.geofabrik.de/europe/cyprus-latest.osm.pbf"

#Россия (на geofabrik.de границы до 2022 года)
wget "https://download.geofabrik.de/russia-latest.osm.pbf"

#Весь мир
wget https://planet.openstreetmap.org/pbf/planet-latest.osm.pbf
```

## Создание векторных карт

```bash
mkdir -m 0777 serve/tiles

#Кипр (для тестирования), ~5-10 минут
docker run -it --rm -v $(pwd)/prepare:/data -v $(pwd)/serve/tiles:/tiles -w /data \
    openjdk:21 \
    java -Xmx1g -jar planetiler.jar \
    --download --fetch-wikidata --nodemap-type=array --storage=ram \
    --osm-path cyprus-latest.osm.pbf \
    --output /tiles/cyprus-latest.pmtiles

#Россия, ~20-30 минут
docker run -it --rm -v $(pwd)/prepare:/data -v $(pwd)/serve/tiles:/tiles -w /data \
    openjdk:21 \
    java -Xmx8g -jar planetiler.jar \
    --download --fetch-wikidata --nodemap-type=array --storage=ram \
    --osm-path russia-latest.osm.pbf \
    --output /tiles/russia-latest.pmtiles

#Весь мир, ~5 часов (16 ядер, 40ГБ RAM, ~500ГБ SSD)
docker run -it --rm -v $(pwd)/prepare:/data -v $(pwd)/serve/tiles:/tiles -w /data \
    openjdk:21 \
    java -Xmx40g -jar planetiler.jar \
    --download --fetch-wikidata --nodemap-type=array --storage=mmap \
    --osm-path planet.osm.pbf \
    --output /tiles/planet.pmtiles
```

Будут созданы файлы `.pmtiles`

## Подготовка и запуск сервиса построения маршрутов

```bash

mkdir -m 0777 osrm

#Кипр (для тестирования), ~1 минута

mkdir -m 0777 osrm/cyprus-car osrm/cyprus-bicycle osrm/cyprus-foot
ln -s ../../prepare/cyprus-latest.osm.pbf osrm/cyprus-car/cyprus-latest.osm.pbf
ln -s ../../prepare/cyprus-latest.osm.pbf osrm/cyprus-bicycle/cyprus-latest.osm.pbf
ln -s ../../prepare/cyprus-latest.osm.pbf osrm/cyprus-foot/cyprus-latest.osm.pbf

docker run -it --rm -v $(pwd):/data \
  ghcr.io/project-osrm/osrm-backend \
  osrm-extract -p /opt/car.lua /data/osrm/cyprus-car/cyprus-latest.osm.pbf
docker run -it --rm -v $(pwd):/data \
  ghcr.io/project-osrm/osrm-backend \
  osrm-partition /data/osrm/cyprus-car/cyprus-latest.osrm
docker run -it --rm -v $(pwd):/data \
  ghcr.io/project-osrm/osrm-backend \
  osrm-customize /data/osrm/cyprus-car/cyprus-latest.osrm

docker run -it --rm -v $(pwd):/data \
  ghcr.io/project-osrm/osrm-backend \
  osrm-extract -p /opt/bicycle.lua /data/osrm/cyprus-bicycle/cyprus-latest.osm.pbf
docker run -it --rm -v $(pwd):/data \
  ghcr.io/project-osrm/osrm-backend \
  osrm-partition /data/osrm/cyprus-bicycle/cyprus-latest.osrm
docker run -it --rm -v $(pwd):/data \
  ghcr.io/project-osrm/osrm-backend \
  osrm-customize /data/osrm/cyprus-bicycle/cyprus-latest.osrm

docker run -it --rm -v $(pwd):/data \
  ghcr.io/project-osrm/osrm-backend \
  osrm-extract -p /opt/foot.lua /data/osrm/cyprus-foot/cyprus-latest.osm.pbf
docker run -it --rm -v $(pwd):/data \
  ghcr.io/project-osrm/osrm-backend \
  osrm-partition /data/osrm/cyprus-foot/cyprus-latest.osrm
docker run -it --rm -v $(pwd):/data \
  ghcr.io/project-osrm/osrm-backend \
  osrm-customize /data/osrm/cyprus-foot/cyprus-latest.osrm

#Россия, ~1час

mkdir -m 0777 osrm/russia-car osrm/russia-bicycle osrm/russia-foot
ln -s ../../prepare/russia-latest.osm.pbf osrm/russia-car/russia-latest.osm.pbf
ln -s ../../prepare/russia-latest.osm.pbf osrm/russia-bicycle/russia-latest.osm.pbf
ln -s ../../prepare/russia-latest.osm.pbf osrm/russia-foot/russia-latest.osm.pbf

docker run -it --rm -v $(pwd):/data \
  ghcr.io/project-osrm/osrm-backend \
  osrm-extract -p /opt/car.lua /data/osrm/russia-car/russia-latest.osm.pbf
docker run -it --rm -v $(pwd):/data \
  ghcr.io/project-osrm/osrm-backend \
  osrm-partition /data/osrm/russia-car/russia-latest.osrm
docker run -it --rm -v $(pwd):/data \
  ghcr.io/project-osrm/osrm-backend \
  osrm-customize /data/osrm/russia-car/russia-latest.osrm

docker run -it --rm -v $(pwd):/data \
  ghcr.io/project-osrm/osrm-backend \
  osrm-extract -p /opt/bicycle.lua /data/osrm/russia-bicycle/russia-latest.osm.pbf
docker run -it --rm -v $(pwd):/data \
  ghcr.io/project-osrm/osrm-backend \
  osrm-partition /data/osrm/russia-bicycle/russia-latest.osrm
docker run -it --rm -v $(pwd):/data \
  ghcr.io/project-osrm/osrm-backend \
  osrm-customize /data/osrm/russia-bicycle/russia-latest.osrm

docker run -it --rm -v $(pwd):/data \
  ghcr.io/project-osrm/osrm-backend \
  osrm-extract -p /opt/foot.lua /data/osrm/russia-foot/russia-latest.osm.pbf
docker run -it --rm -v $(pwd):/data \
  ghcr.io/project-osrm/osrm-backend \
  osrm-partition /data/osrm/russia-foot/russia-latest.osrm
docker run -it --rm -v $(pwd):/data \
  ghcr.io/project-osrm/osrm-backend \
  osrm-customize /data/osrm/russia-foot/russia-latest.osrm

#Весь мир

#TODO >40GB RAM required

#Запуск

docker compose -f osrm-docker-compose.yml up -d

```

## Создание БД и запуск сервиса геокодирования

```bash
mkdir -m 0777 nominatim

wget -O "nominatim/wikimedia-importance.sql.gz" "https://nominatim.org/data/wikimedia-importance.sql.gz"
wget -O "nominatim/secondary_importance.sql.gz" "https://nominatim.org/data/wikimedia-secondary-importance.sql.gz"

#Кипр (для тестирования) ~10 минут
mkdir -m 0777 nominatim/cyprus-flatnode nominatim/cyprus-data
docker compose -f nominatim-cyprus-docker-compose.yml up -d

#Россия
mkdir -m 0777 nominatim/russia-flatnode nominatim/russia-data
docker compose -f nominatim-russia-docker-compose.yml up -d

#Весь мир

#TODO
```

## Подготовка сервера

```bash
cd serve

mkdir -m 0777 nginx_cache nginx_logs

git clone "https://github.com/openmaptiles/fonts" fonts

cd static

npm install

cd ../..
```

## Запуск сервера

```bash
docker compose -f tiles-docker-compose.yml up -d
```

Открыть http://localhost:8080/
