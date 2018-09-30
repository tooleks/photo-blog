<template>
    <div>
        <round-spinner :loading="loading"></round-spinner>
        <gallery-viewer v-if="activePhoto"
                        :activeImage.sync="activePhoto"
                        :images="photos"
                        @onFirstImage="loadNewerPhoto"
                        @onLastImage="loadOlderPhoto"
                        @onExit="goToPhotosPage"></gallery-viewer>
        <div class="container" v-if="activePhoto">
            <div class="row">
                <div class="col">
                    <photo-description-card :photo="activePhoto" @onBack="goToPhotosPage"></photo-description-card>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {optional as opt} from "tooleks";
    import RoundSpinner from "../utils/RoundSpinner";
    import Viewer from "../gallery/Viewer";
    import PhotoDescriptionCard from "./PhotoDescriptionCard";
    import {GoToMixin, MetaMixin} from "../../mixins";

    export default {
        components: {
            RoundSpinner,
            PhotoDescriptionCard,
            GalleryViewer: Viewer,
        },
        mixins: [
            GoToMixin,
            MetaMixin,
        ],
        data: function () {
            return {
                /** @type {boolean} */
                loading: false,
                /** @type {Array<Photo>} */
                photos: [],
                /** @type {Photo} */
                activePhoto: null,
            };
        },
        watch: {
            "$route.params.id": function (id) {
                const photo = this.photos.find((photo) => photo.postId === Number(id));
                if (photo) {
                    this.activePhoto = photo;
                    return;
                }

                this.goToNotFoundPage();
            },
            activePhoto: function (activePhoto) {
                this.goToPhotoPage(activePhoto.postId);
                this.setPageTitle(activePhoto.description);
                this.setPageImage(activePhoto.image.url);
            },
        },
        methods: {
            loadPhoto: async function () {
                this.loading = true;
                try {
                    const photo = await this.$services.getPhotoManager().getByPostId(
                        this.$route.params.id,
                        this.$route.query,
                    );
                    this.photos = [photo];
                    this.activePhoto = photo;
                } catch (error) {
                    if (opt(() => error.response.status) === 404) {
                        this.goToNotFoundPage();
                    } else {
                        throw error;
                    }
                } finally {
                    this.loading = false;
                }
            },
            loadOlderPhoto: async function () {
                this.loading = true;
                try {
                    const photo = await this.$services.getPhotoManager().getPreviousByPostId(
                        this.activePhoto.postId,
                        this.$route.query,
                    );
                    this.photos = [...this.photos, photo];
                } catch (error) {
                    // The error is handled by the API service.
                    // No additional actions needed.
                } finally {
                    this.loading = false;
                }
            },
            loadNewerPhoto: async function () {
                this.loading = true;
                try {
                    const photo = await this.$services.getPhotoManager().getNextByPostId(
                        this.activePhoto.postId,
                        this.$route.query,
                    );
                    this.photos = [photo, ...this.photos];
                } catch (error) {
                    // The error is handled by the API service.
                    // No additional actions needed.
                } finally {
                    this.loading = false;
                }
            },
        },
        created: function () {
            this.loadPhoto();
        },
    }
</script>
