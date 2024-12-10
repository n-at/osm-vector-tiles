(() => {

    let regionId = null;
    let map = null;

    function init(_regionId) {
        regionId = _regionId;

        map = new maplibregl.Map({
            container: 'map',
            style: `/style-${regionId}.json`,
            center: [33.350276, 35.154792],
            zoom: 10,
        });

        map.addControl(new MaplibreGeocoder(getGeocoderConfig(), { maplibregl }));

        map.addControl(new maplibregl.NavigationControl());

        map.addControl(new maplibregl.ScaleControl({
            unit: 'metric',
            maxWidth: 100,
        }));
    }

    function getGeocoderConfig() {
        return {
            forwardGeocode: async (config) => {
                const features = [];
                try {
                    const request =`/nominatim-${regionId}/search?q=${config.query}&format=geojson&polygon_geojson=1&addressdetails=1`;
                    const response = await fetch(request);
                    const geojson = await response.json();
                    for (const feature of geojson.features) {
                        const center = [
                            feature.bbox[0] +
                        (feature.bbox[2] - feature.bbox[0]) / 2,
                            feature.bbox[1] +
                        (feature.bbox[3] - feature.bbox[1]) / 2
                        ];
                        const point = {
                            type: 'Feature',
                            geometry: {
                                type: 'Point',
                                coordinates: center
                            },
                            place_name: feature.properties.display_name,
                            properties: feature.properties,
                            text: feature.properties.display_name,
                            place_type: ['place'],
                            center,
                        };
                        features.push(point);
                    }
                } catch (e) {
                    console.error(`Failed to forwardGeocode with error: ${e}`);
                }
    
                return {
                    features
                };
            },
        };
    }

    window.MapInit = init;
})();
