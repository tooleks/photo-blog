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
                markerCluster: null,
            };
        },
        watch: {
            images(images) {
                this.destroyMarkerCluster();
                this.initMarkerCluster();
                images.forEach((image) => this.renderImage(image));
            },
        },
        methods: {
            initMarkerCluster() {
                this.markerCluster = L.markerClusterGroup({
                    spiderLegPolylineOptions: {opacity: 0},
                });
                this.map.addLayer(this.markerCluster);
            },
            destroyMarkerCluster() {
                if (this.markerCluster !== null) {
                    this.map.removeLayer(this.markerCluster);
                    this.markerCluster = null;
                }
            },
            renderImage(image) {
                const icon = L.divIcon({html: provideImageMarkerHtml(image)});
                const marker = L.marker(image.location, {icon, riseOnHover: true});
                this.markerCluster.addLayer(marker);
            },
        },
        mounted() {
            this.initMarkerCluster();
        },
    }
</script>
