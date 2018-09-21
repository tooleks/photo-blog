<template>
    <div class="photo-map">
        <loader :loading="loading"></loader>
        <image-map :images="images" :zoom="5"></image-map>
    </div>
</template>

<style scoped>
    .photo-map {
        display: block;

        width: 100%;
        height: 85vh;
    }
</style>

<script>
    import Loader from "../utils/Loader";
    import ImageMap from "../map/ImageMap";
    import {MetaMixin} from "../../mixins";

    export default {
        components: {
            Loader,
            ImageMap,
        },
        mixins: [
            MetaMixin,
        ],
        data: function () {
            return {
                loading: false,
                images: [],
            };
        },
        computed: {
            pageTitle: function () {
                return "Map";
            },
        },
        methods: {
            init: function () {
                this.loadImages();
            },
            loadImages: async function () {
                this.loading = true;
                try {
                    this.images = await this.$dc.get("photoMap").getImages();
                } finally {
                    this.loading = false;
                }
            },
        },
        created: function () {
            this.init();
        },
    }
</script>
