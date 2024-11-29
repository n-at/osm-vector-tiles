# OpenStreetMap Vector Tiles

Пример подготовки векторных карт на основе OpenStreetMap.

Используется:

+ [onthegomap/planetiler](https://github.com/onthegomap/planetiler) для преобразования osm.pbf в pmtiles
+ [maplibre/martin](https://github.com/maplibre/martin) в качестве сервера
+ [maplibre/maplibre-gl-js](https://github.com/maplibre/maplibre-gl-js) для отображения карты
+ [openmaptiles/fonts](https://github.com/openmaptiles/fonts) - шрифты
+ [openmaptiles/osm-bright-gl-style](https://github.com/openmaptiles/osm-bright-gl-style) - тема и иконки
+ [openmaptiles/openmaptiles](https://github.com/openmaptiles/openmaptiles/tree/master/style) - иконки OSM Carto

## Подготовка к созданию карты

```bash
mkdir -m 0777 prepare

cd prepare

wget https://github.com/onthegomap/planetiler/releases/latest/download/planetiler.jar

wget "https://download.geofabrik.de/europe/cyprus-latest.osm.pbf"
#wget "https://download.geofabrik.de/russia-latest.osm.pbf"
#wget https://planet.openstreetmap.org/pbf/planet-latest.osm.pbf
```

## Создание векторных карт

```bash
docker run -it --rm -v $(pwd)/prepare:/data -w /data openjdk:21 \
    java -Xmx1g -jar planetiler.jar --download --output cyprus-latest.pmtiles --osm-path cyprus-latest.osm.pbf

docker run -it --rm -v $(pwd)/prepare:/data -w /data openjdk:21 \
    java -Xmx4g -jar planetiler.jar --download --output russia-latest.pmtiles --osm-path russia-latest.osm.pbf
```

Будут созданы файлы `.pmtiles`

## Подготовка сервера

```bash
cd serve

mkdir -m 0777 nginx_cache nginx_logs

git clone "https://github.com/openmaptiles/fonts" fonts

mv ../prepare/cyprus-latest.pmtiles tiles/
# mv ../prepare/russia-latest.pmtiles tiles/
# mv ../prepare/planet.pmtiles tiles/

cd static
npm install
```

## Запуск сервера

```bash
cd serve
docker compose up -d
```

Открыть http://localhost:8080
