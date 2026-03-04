# OpenStreetMap Vector Tiles

Пример подготовки векторных карт на основе OpenStreetMap.

Используется:

+ [onthegomap/planetiler](https://github.com/onthegomap/planetiler) для преобразования osm.pbf в pmtiles (Apache-2.0)
+ [maplibre/martin](https://github.com/maplibre/martin) в качестве сервера (Apache-2.0)
+ [maplibre/maplibre-gl-js](https://github.com/maplibre/maplibre-gl-js) для отображения карты (BSD-3-Clause)
+ [openmaptiles/openmaptiles](https://github.com/openmaptiles/openmaptiles) - спецификация и стандартный стиль карты (BSD-3-Clause, CC-BY 4.0)
+ [openmaptiles/fonts](https://github.com/openmaptiles/fonts) - шрифты (OFL, Apache-2.0)
+ [openmaptiles/osm-bright-gl-style](https://github.com/openmaptiles/osm-bright-gl-style) - тема и иконки (BSD-3-Clause, CC-BY 4.0)
+ [gravitystorm/openstreetmap-carto](https://github.com/gravitystorm/openstreetmap-carto) - иконки оригинальной темы OSM Carto (CC0)

Для запуска потребуется docker, git.

Настройка и подключение OSRM для построения маршрутов рассматривается в [OSRM](osrm/README.md)

Настройка и подключение Nominatim для прямого и обратного геокодирования рассматривается в [Nominatim](nominatim/README.md)

## Подготовка к созданию карты и создание векторных карт

Сначала скачивается planetiler:

```bash
mkdir -m 0777 prepare
wget -O "prepare/planetiler.jar" https://github.com/onthegomap/planetiler/releases/latest/download/planetiler.jar
```

Для подготовки карты можно выбрать один из регионов (либо скачать нужный с geofabrik.de):

```bash
###############################################################################
# Кипр (для тестирования)
###############################################################################

wget -O "prepare/cyprus-latest.osm.pbf" "https://download.geofabrik.de/europe/cyprus-latest.osm.pbf"

docker run -it --rm -v $(pwd)/prepare:/data -w /data \
    eclipse-temurin:21-alpine \
    java -Xmx1g -jar planetiler.jar \
    --download --fetch-wikidata --nodemap-type=array --storage=mmap \
    --osm-path cyprus-latest.osm.pbf \
    --output /data/cyprus-latest.pmtiles

###############################################################################
# Россия, СЗФО
###############################################################################

wget -O "prepare/russia-szfo-latest.osm.pbf" "https://download.geofabrik.de/russia/northwestern-fed-district-latest.osm.pbf"

docker run -it --rm -v $(pwd)/prepare:/data -w /data \
    eclipse-temurin:21-alpine \
    java -Xmx8g -jar planetiler.jar \
    --download --fetch-wikidata --nodemap-type=array --storage=mmap \
    --osm-path russia-szfo-latest.osm.pbf \
    --output /data/russia-szfo-latest.pmtiles

###############################################################################
# Россия (на geofabrik.de границы до 2022 года)
###############################################################################

wget -O "prepare/russia-latest.osm.pbf" "https://download.geofabrik.de/russia-latest.osm.pbf"

docker run -it --rm -v $(pwd)/prepare:/data -w /data \
    eclipse-temurin:21-alpine \
    java -Xmx8g -jar planetiler.jar \
    --download --fetch-wikidata --nodemap-type=array --storage=mmap \
    --osm-path russia-latest.osm.pbf \
    --output /data/russia-latest.pmtiles

###############################################################################
# Весь мир
###############################################################################

wget -O "prepare/planet-latest.osm.pbf" https://planet.openstreetmap.org/pbf/planet-latest.osm.pbf

docker run -it --rm -v $(pwd)/prepare:/data -w /data \
    eclipse-temurin:21-alpine \
    java -Xmx40g -jar planetiler.jar \
    --download --fetch-wikidata --nodemap-type=array --storage=mmap \
    --osm-path planet-latest.osm.pbf \
    --output /data/planet-latest.pmtiles    
```

В результате в каталоге `prepare` будет создан файл с расширением `.pmtiles`.

При проблемах скачивания файлов с данными для planetiler, можно запустить вручную:

```bash
cd prepare/data/sources
wget "https://osmdata.openstreetmap.de/download/water-polygons-split-3857.zip"
wget "https://naciscdn.org/naturalearth/packages/natural_earth_vector.sqlite.zip"
cd ../../..
```

## Правка стилей карты

Стили карты собираются из слоев, описанных в файлах JSON в каталоге `styles/layers`.
Схема и документация по стилям находится на странице [openmaptiles.org/schema](https://openmaptiles.org/schema/).

`_` перед именем файла отключает его добавление в итоговый файл стиля.
По умолчанию скрыты границы государств и регионов, а также их наименования.

В файле `styles/combine_layers.php` можно настроить параметры карты по умолчанию.

Сборка стиля:

```bash
docker run --rm -v $(pwd):/data -w /data/styles \
    php:8.4-cli-alpine \
    php combine_layers.php
```

Собранный файл помещается в `web/style.json`

## Подготовка сервера

```bash
mkdir -m 0777 nginx_cache nginx_logs

git clone "https://github.com/openmaptiles/fonts" fonts

docker run --rm -v $(pwd):/data -w /data/web \
    node:24-alpine \
    npm install
```

## Запуск сервера

```bash
docker compose up -d
```

Открыть http://localhost:8080/index.html для версии на основе maplibre-gl

Открыть http://localhost:8080/index-leaflet.html для версии на основе leaflet.js
