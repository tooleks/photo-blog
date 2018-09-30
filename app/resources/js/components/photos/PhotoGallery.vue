<template>
    <div class="container">
        <round-spinner :loading="loading"></round-spinner>
        <div v-if="photos.length" class="row">
            <div class="col py-1">
                <gallery-masonry ref="masonry" :images="photos"></gallery-masonry>
            </div>
        </div>
        <div v-if="!loading && !photos.length" class="row">
            <div class="col mt-3">
                <div class="alert alert-secondary">No photos found</div>
            </div>
        </div>
        <div v-if="previousPageExists || nextPageExists" class="row">
            <div class="col mt-2">
                <router-link
                        v-if="previousPageExists"
                        :to="{name: this.routeName, params: {page: this.previousPage}}"
                        class="btn btn-secondary float-left"
                        title="Previous Page">Previous
                </router-link>
                <router-link
                        v-if="nextPageExists"
                        :to="{name: this.routeName, params: {page: this.nextPage}}"
                        class="btn btn-secondary float-right"
                        title="Next Page">Next
                </router-link>
            </div>
        </div>
    </div>
</template>

<script>
    import {waitUntil} from "tooleks";
    import RoundSpinner from "../utils/RoundSpinner";
    import Masonry from "../gallery/Masonry";
    import {GoToMixin, MetaMixin} from "../../mixins";

    export default {
        components: {
            RoundSpinner,
            GalleryMasonry: Masonry,
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
                /** @type {number|null} */
                previousPage: null,
                /** @type {number} */
                currentPage: 1,
                /** @type {number|null} */
                nextPage: null,
                /** @type {boolean} */
                previousPageExists: false,
                /** @type {boolean} */
                nextPageExists: false,
            };
        },
        watch: {
            "$route": function () {
                this.init();
            },
            currentPage: function (currentPage) {
                if (currentPage > 1) {
                    this.$router.push({
                        name: this.routeName,
                        params: {page: currentPage},
                        hash: this.$route.hash,
                    });
                }
            },
            photos: function () {
                // Wait until "masonry" reference will be available then scroll to an active image.
                waitUntil(() => this.$refs.masonry).then((masonry) => {
                    const id = this.$route.hash.slice(1);
                    if (id) {
                        masonry.scrollToImageById(id);
                    }
                });
            },
        },
        methods: {
            init: function () {
                if (this.$route.query.show) {
                    // #bc: Go to a new photo page in order to preserve backward compatibility
                    // with an older version of the application.
                    this.goToPhotoPage(this.$route.query.show);
                } else {
                    this.loadPhotos();
                }
            },
            setPhotos: function ({items, previousPageExists, nextPageExists, currentPage, nextPage, previousPage}) {
                this.photos = items;
                this.previousPageExists = previousPageExists;
                this.nextPageExists = nextPageExists;
                this.currentPage = currentPage;
                this.nextPage = nextPage;
                this.previousPage = previousPage;
            },
            loadPhotos: async function () {
                this.loading = true;
                try {
                    this.setPhotos(await this.$services.getPhotoManager().paginate(this.$route.params));
                } catch (error) {
                    // The error is handled by the API service.
                    // No additional actions needed.
                } finally {
                    this.loading = false;
                }
            },
        },
        created: function () {
            this.init();
            if (this.$route.params.searchPhrase) {
                this.setPageTitle(`Search "${this.$route.params.searchPhrase}"`);
            } else if (this.$route.params.tag) {
                this.setPageTitle(`Search by tag #${this.$route.params.tag}`);
            } else {
                this.setPageTitle("All photos");
            }
        },
    }
</script>
