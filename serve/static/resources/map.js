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

        map.addControl(new maplibregl.NavigationControl());

        map.addControl(new maplibregl.ScaleControl({
            unit: 'metric',
            maxWidth: 100,
        }));
    }

    window.MapInit = init;
})();
