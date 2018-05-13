<template>
    <basic-map ref="map" :lat="lat" :lng="lng" :zoom="this.zoom"></basic-map>
</template>

<script>
    import L from "leaflet";
    import BasicMap from "./basic-map";
    import {provideLocationPopupHtml} from "./location-popup-provider";

    export default {
        components: {
            BasicMap,
        },
        props: {
            lat: {
                type: Number,
            },
            lng: {
                type: Number,
            },
            zoom: {
                type: Number,
            },
            disabled: {
                type: Boolean,
                default: false,
            },
        },
        data: function () {
            return {
                marker: null,
            };
        },
        computed: {
            map: function () {
                return this.$refs.map.getMap();
            },
        },
        watch: {
            lat: function (lat) {
                if (typeof lat !== "undefined" && typeof this.lng !== "undefined") {
                    this.setMarker(lat, this.lng);
                }
            },
            lng: function (lng) {
                if (typeof this.lat !== "undefined" && typeof lng !== "undefined") {
                    this.setMarker(this.lat, lng);
                }
            },
        },
        methods: {
            init: function () {
                // Handle map click events if component is not disabled.
                if (!this.disabled) {
                    this.map.on("click", (event) => this.setMarker(event.latlng.lat, event.latlng.lng));
                }

                // Enable scroll wheel zooming only when map is in focus.
                this.map.scrollWheelZoom.disable();
                this.map.on("blur", (event) => event.target.scrollWheelZoom.disable());
                this.map.on("focus", (event) => event.target.scrollWheelZoom.enable());

                // Set marker if latitude and longitude are initialized.
                if (typeof this.lat !== "undefined" && typeof this.lng !== "undefined") {
                    this.setMarker(this.lat, this.lng);
                }
            },
            setMarker: function (lat, lng) {
                // If marker is already initialized remove it from the map.
                if (this.marker !== null) {
                    this.map.removeLayer(this.marker);
                }

                this.marker = L.marker([lat, lng], {draggable: !this.disabled}).addTo(this.$refs.map.getMap());
                // Show marker popup.
                this.map.on("click", () => this.showMarkerPopup());
                this.showMarkerPopup();

                // Emit lat, lng properties changes.
                this.$emit("update:lat", lat);
                this.$emit("update:lng", lng);

                // Handle marker drag events if component is not disabled.
                if (!this.disabled) {
                    this.marker.on("dragend", (event) => this.setMarker(event.target.getLatLng().lat, event.target.getLatLng().lng));
                }
            },
            showMarkerPopup: function () {
                if (this.marker !== null) {
                    const lat = this.marker.getLatLng().lat;
                    const lng = this.marker.getLatLng().lng;
                    provideLocationPopupHtml({lat, lng}).then((html) => this.marker.bindPopup(html).openPopup());
                }
            },
        },
        mounted: function () {
            this.init();
        },
    }
</script>
