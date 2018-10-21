<script>
    import BasicMap from "./BasicMap";
    import {provideImageMarkerHtml} from "./imageMarkerProvider";

    export default {
        extends: BasicMap,
        props: {
            images: {
                type: Array,
                default() {
                    return [];
                },
            },
        },
        data() {
            return {
                markerGroup: null,
            };
        },
        watch: {
            images(images) {
                this.destroyMarkerCluster();
                this.initMarkerCluster();
                this.renderImages(images);
            },
        },
        methods: {
            initMarkerCluster() {
                this.markerGroup = L.markerClusterGroup({
                    spiderLegPolylineOptions: {opacity: 0},
                });
                this.map.addLayer(this.markerGroup);
            },
            destroyMarkerCluster() {
                if (this.markerGroup !== null) {
                    this.map.removeLayer(this.markerGroup);
                    this.markerGroup = null;
                }
            },
            renderImages(images) {
                images.forEach((image) => {
                    const icon = L.divIcon({html: provideImageMarkerHtml(image)});
                    const marker = L.marker(image.location, {icon, riseOnHover: true});
                    this.markerGroup.addLayer(marker);
                });

                const bounds = this.markerGroup.getBounds();
                if (bounds.isValid()) {
                    this.map.fitBounds(this.markerGroup.getBounds());
                }
            },
        },
        mounted() {
            this.initMarkerCluster();
        },
    }
</script>
