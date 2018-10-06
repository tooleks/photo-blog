<template>
    <div>
        <round-spinner :loading="loading"/>
        <gallery-viewer v-if="activePhoto"
                        :activeImage.sync="activePhoto"
                        :images="photos"
                        @onFirstImage="loadNewerPhoto"
                        @onLastImage="loadOlderPhoto"
                        @onExit="goToPhotosPage"/>
        <div class="container" v-if="activePhoto">
            <div class="row">
                <div class="col">
                    <photo-description-card :photo="activePhoto" @onBack="goToPhotosPage"/>
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
    import {MetaMixin, RouteMixin} from "../../mixins";

    export default {
        components: {
            RoundSpinner,
            PhotoDescriptionCard,
            GalleryViewer: Viewer,
        },
        mixins: [
            RouteMixin,
            MetaMixin,
        ],
        data() {
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
            ["$route.params.id"](id) {
                const photo = this.photos.find((photo) => photo.postId === Number(id));
                if (photo) {
                    this.activePhoto = photo;
                    return;
                }
                this.goToNotFoundPage();
            },
            activePhoto(activePhoto) {
                this.goToPhotoPage(activePhoto.postId);
                this.setPageTitle(activePhoto.description);
                this.setPageImage(activePhoto.image.url);
            },
        },
        methods: {
            async loadPhoto() {
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
            async loadOlderPhoto() {
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
            async loadNewerPhoto() {
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
        created() {
            this.loadPhoto();
        },
    }
</script>
