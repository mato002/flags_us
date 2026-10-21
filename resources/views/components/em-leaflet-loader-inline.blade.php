@once('emEffectiveMediaLeafletLoader')
    <script>
        (() => {
            window.__emLeafletPromises = window.__emLeafletPromises || {};

            window.emLoadLeaflet = function emLoadLeaflet(opts = {}) {
                const loadCss = (href) =>
                    new Promise((resolve) => {
                        const link = document.createElement('link');
                        link.rel = 'stylesheet';
                        link.href = href;
                        link.onload = () => resolve();
                        document.head.appendChild(link);
                    });

                const loadScript = (src) =>
                    new Promise((resolve, reject) => {
                        const script = document.createElement('script');
                        script.src = src;
                        script.async = true;
                        script.onload = () => resolve();
                        script.onerror = reject;
                        document.body.appendChild(script);
                    });

                const needCluster = !!opts.withMarkerCluster;
                const needHeat = !!opts.withHeat;

                if (
                    window.L
                    && (!needCluster || typeof L.markerClusterGroup === 'function')
                    && (!needHeat || typeof L.heatLayer === 'function')
                ) {
                    return Promise.resolve();
                }

                const key = [needCluster ? 'c' : '', needHeat ? 'h' : ''].join('') || 'base';

                if (!window.__emLeafletPromises[key]) {
                    window.__emLeafletPromises[key] = (async () => {
                        if (!window.L) {
                            await loadCss('https://unpkg.com/leaflet@1.9.4/dist/leaflet.css');
                            await loadScript('https://unpkg.com/leaflet@1.9.4/dist/leaflet.js');
                        }

                        if (
                            needCluster
                            && window.L
                            && typeof L.markerClusterGroup !== 'function'
                        ) {
                            await loadCss('https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css');
                            await loadCss('https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css');
                            await loadScript('https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js');
                        }

                        if (needHeat && window.L && typeof L.heatLayer !== 'function') {
                            await loadScript('https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js');
                        }
                    })();
                }

                return window.__emLeafletPromises[key];
            };
        })();
    </script>
@endonce
