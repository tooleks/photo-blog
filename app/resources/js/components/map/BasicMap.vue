<template>
    <div :id="id" class="leaflet-container"></div>
</template>

<style>
    .leaflet-container {
        display: block;
        width: 100%;
        height: 100%;
        min-width: 200px;
        min-height: 350px;
        font-size: 13px !important;
        font-family: inherit !important;
    }

    .leaflet-popup-content-wrapper {
        border-radius: 2px !important;
    }

    .leaflet-left .leaflet-control {
        cursor: pointer;
    }
</style>

<script>
    import L from "leaflet";
    import "leaflet/dist/leaflet.css";
    import "leaflet.locatecontrol";
    import "leaflet.markercluster";
    import "leaflet.markercluster/dist/MarkerCluster.Default.css";
    import markerIconRetinaUrl from "leaflet/dist/images/marker-icon-2x.png";
    import markerIconUrl from "leaflet/dist/images/marker-icon.png";
    import markerShadowUrl from "leaflet/dist/images/marker-shadow.png";
    import Location from "../../entities/Location";

    // Workaround for issue: https://github.com/Leaflet/Leaflet/issues/4968#issuecomment-269750768
    delete L.Icon.Default.prototype._getIconUrl;
    L.Icon.Default.mergeOptions({
        iconRetinaUrl: markerIconRetinaUrl,
        iconUrl: markerIconUrl,
        shadowUrl: markerShadowUrl,
    });

    export default {
        props: {
            id: {
                type: String,
                default() {
                    const id = Math.random().toString(36).substr(2, 5);
                    return `map-${id}`;
                },
            },
            tileLayerUrl: {
                type: String,
                default: "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
            },
            tileLayerOptions: {
                type: Object,
                default() {
                    return {
                        maxZoom: 18,
                        attribution: `&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>`,
                    };
                },
            },
            center: {
                type: Object,
                validator({lat, lng}) {
                    try {
                        // Location constructor will throw an exception if invalid coordinates will be provided.
                        new Location({lat, lng});
                        return true;
                    } catch (error) {
                        return false;
                    }
                },
                default() {
                    // Lviv, Ukraine.
                    return {
                        lat: 49.85,
                        lng: 24.0166666667,
                    };
                },
            },
            zoom: {
                type: Number,
                default: 8,
            },
        },
        data() {
            return {
                /** @type {Array<Function>} */
                intervals: [],
                map: null,
                locationControl: null,
            };
        },
        watch: {
            center(center) {
                this.map.panTo(center);
            },
            zoom(zoom) {
                this.map.setView(this.map.getCenter(), zoom);
            },
        },
        methods: {
            handleMapEvent(event) {
                this.$emit("update:location", event.target.getCenter());
                this.$emit("update:zoom", event.target.getZoom());
                this.$emit("bounds", {
                    southWest: event.target.getBounds().getSouthWest(),
                    northEast: event.target.getBounds().getNorthEast(),
                });
            },
        },
        mounted() {
            this.map = L.map(this.id).setView(this.center, this.zoom);
            L.tileLayer(this.tileLayerUrl, this.tileLayerOptions).addTo(this.map);
            this.locationControl = L.control.locate().addTo(this.map);
            this.map.on("zoomend", this.handleMapEvent);
            this.map.on("moveend", this.handleMapEvent);
            this.intervals.push(setInterval(() => this.map.invalidateSize(), 100));
        },
        beforeDestroy() {
            this.map.remove();
            this.map = null;
            this.locationControl = null;
            this.intervals.forEach((interval) => clearInterval(interval));
        },
    }
</script>
