<template>
    <div class="photo-map">
        <round-spinner :loading="loading"/>
        <image-map :images="images" :zoom="5"/>
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
    import RoundSpinner from "../utils/RoundSpinner";
    import ImageMap from "../map/ImageMap";
    import {MetaMixin} from "../../mixins";

    export default {
        components: {
            RoundSpinner,
            ImageMap,
        },
        mixins: [
            MetaMixin,
        ],
        data() {
            return {
                /** @type {boolean} */
                loading: false,
                /** @type {Object} */
                images: [],
            };
        },
        methods: {
            async loadImages() {
                this.loading = true;
                try {
                    const photos = await this.$services.getPhotoManager().getAll();
                    this.images = photos.filter((photo) => photo.location).map((photo) => {
                        return {
                            imageUrl: photo.original.url,
                            linkUrl: `/photo/${encodeURIComponent(photo.postId)}`,
                            title: photo.description,
                            location: photo.location,
                        };
                    });
                } finally {
                    this.loading = false;
                }
            },
        },
        created() {
            this.setPageTitle(this.$lang("Photo Map"));
            this.loadImages();
        },
    }
</script>
