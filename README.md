# OpenStreetMap Vector Tiles

> TODO в процессе переработки

Пример подготовки векторных карт на основе OpenStreetMap.

Используется:

+ [onthegomap/planetiler](https://github.com/onthegomap/planetiler) для преобразования osm.pbf в pmtiles (Apache-2.0)
+ [maplibre/martin](https://github.com/maplibre/martin) в качестве сервера (Apache-2.0)
+ [maplibre/maplibre-gl-js](https://github.com/maplibre/maplibre-gl-js) для отображения карты (BSD-3-Clause)
+ [/openmaptiles/openmaptiles](https://github.com/openmaptiles/openmaptiles) - спецификация и стандартный стиль карты (BSD-3-Clause, CC-BY 4.0)
+ [openmaptiles/fonts](https://github.com/openmaptiles/fonts) - шрифты (OFL, Apache-2.0)
+ [openmaptiles/osm-bright-gl-style](https://github.com/openmaptiles/osm-bright-gl-style) - тема и иконки (BSD-3-Clause, CC-BY 4.0)
+ [gravitystorm/openstreetmap-carto](https://github.com/gravitystorm/openstreetmap-carto) - иконки оригинальной темы OSM Carto (CC0)

Настройка и подключение OSRM для построения маршрутов рассматривается в [OSRM](osrm/README.md)

Настройка и подключение Nominatim для прямого и обратного геокодирования рассматривается в [Nominatim](nominatim/README.md)

## Подготовка к созданию карты и создание векторных карт

Сначала скачивается planetiler:

```bash
mkdir -m 0777 prepare
wget -O "planetiler.jar" https://github.com/onthegomap/planetiler/releases/latest/download/planetiler.jar
```

Для подготовки карты можно выбрать один из регионов (либо скачать нужный с geofabrik.de):

```bash
###############################################################################
# Кипр (для тестирования)
###############################################################################

wget -O "cyprus-latest.osm.pbf" "https://download.geofabrik.de/europe/cyprus-latest.osm.pbf"

docker run -it --rm -v $(pwd)/prepare:/data -w /data \
    eclipse-temurin:21 \
    java -Xmx1g -jar planetiler.jar \
    --download --fetch-wikidata --nodemap-type=array --storage=mmap \
    --osm-path cyprus-latest.osm.pbf \
    --output /data/cyprus-latest.pmtiles

###############################################################################
# Россия, СЗФО
###############################################################################

wget -O "russia-szfo-latest.osm.pbf" "https://download.geofabrik.de/russia/northwestern-fed-district-latest.osm.pbf"

docker run -it --rm -v $(pwd)/prepare:/data -w /data \
    eclipse-temurin:21 \
    java -Xmx8g -jar planetiler.jar \
    --download --fetch-wikidata --nodemap-type=array --storage=mmap \
    --osm-path russia-szfo-latest.osm.pbf \
    --output /data/russia-szfo-latest.pmtiles

###############################################################################
# Россия (на geofabrik.de границы до 2022 года)
###############################################################################

wget -O "russia-latest.osm.pbf" "https://download.geofabrik.de/russia-latest.osm.pbf"

docker run -it --rm -v $(pwd)/prepare:/data -w /data \
    eclipse-temurin:21 \
    java -Xmx8g -jar planetiler.jar \
    --download --fetch-wikidata --nodemap-type=array --storage=mmap \
    --osm-path russia-latest.osm.pbf \
    --output /data/russia-latest.pmtiles

###############################################################################
# Весь мир
###############################################################################

wget -O "planet-latest.osm.pbf" https://planet.openstreetmap.org/pbf/planet-latest.osm.pbf

docker run -it --rm -v $(pwd)/prepare:/data -w /data \
    eclipse-temurin:21 \
    java -Xmx40g -jar planetiler.jar \
    --download --fetch-wikidata --nodemap-type=array --storage=mmap \
    --osm-path planet-latest.osm.pbf \
    --output /data/planet-latest.pmtiles    
```

В результате в каталоге `prepare` получится файл с расширением `.pmtiles`.

При проблемах скачивания файлов с данными для planetiler можно запустить вручную:

```bash
cd prepare/data/sources
wget "https://osmdata.openstreetmap.de/download/water-polygons-split-3857.zip"
wget "https://naciscdn.org/naturalearth/packages/natural_earth_vector.sqlite.zip"
cd ../../..
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
docker compose up -d
```

Открыть http://localhost:8080/
