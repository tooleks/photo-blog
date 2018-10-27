<template>
    <basic-map ref="map"
               :center="location"
               :zoom="zoom"/>
</template>

<script>
    import L from "leaflet";
    import BasicMap from "./BasicMap";
    import Location from "../../entities/Location";
    import {provideLocationPopupHtml} from "./locationPopupProvider";

    export default {
        components: {
            BasicMap,
        },
        props: {
            location: {
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
            },
            zoom: Number,
            disabled: {
                type: Boolean,
                default: false,
            },
        },
        data() {
            return {
                marker: null,
            };
        },
        computed: {
            map() {
                return this.$refs.map.map;
            },
            locationControl() {
                return this.$refs.map.locationControl;
            },
        },
        watch: {
            location(location) {
                this.resetMarker(location);
            },
        },
        methods: {
            init() {
                // Handle scroll events if the map is in a focus.
                this.map.scrollWheelZoom.disable();
                this.map.on("blur", (event) => event.target.scrollWheelZoom.disable());
                this.map.on("focus", (event) => event.target.scrollWheelZoom.enable());

                this.resetMarker(this.location);

                // Handle map click events if the component is not disabled.
                if (!this.disabled) {
                    this.map.on("click", (event) => this.resetMarker(event.latlng));
                }

                // If marker location is not provided use geolocation to position map.
                if (!this.location) {
                    this.locationControl.start();
                }
            },
            setMarker(location) {
                this.marker = L.marker(location, {draggable: !this.disabled}).addTo(this.map);
                // Show marker popup.
                this.map.on("click", () => this.showMarkerPopup());
                this.showMarkerPopup();

                // Handle marker drag events if component is not disabled.
                if (!this.disabled) {
                    this.marker.on("dragend", (event) => this.resetMarker(event.target.getLatLng()));
                }

                this.$emit("update:location", location);
            },
            removeMarker() {
                if (this.marker !== null) {
                    this.map.removeLayer(this.marker);
                    this.marker = null;
                }
            },
            resetMarker(location) {
                this.removeMarker();
                if (location) {
                    this.setMarker(location);
                }
            },
            async showMarkerPopup() {
                if (this.marker !== null) {
                    const location = this.marker.getLatLng();
                    const html = await provideLocationPopupHtml({location});
                    this.marker.bindPopup(html).openPopup();
                }
            },
        },
        mounted() {
            this.init();
        },
    }
</script>
