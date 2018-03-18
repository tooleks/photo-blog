<template>
    <div class="container">
        <loader :loading="loading"></loader>
        <div v-if="photos.length" class="row">
            <div class="col py-1">
                <masonry :images="photos"></masonry>
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
    import Loader from "../utils/loader";
    import Masonry from "../gallery/masonry";
    import {GotoMixin, MetaMixin} from "../../mixins";
    import {photoService} from "../../services";

    export default {
        components: {
            Loader,
            Masonry,
        },
        mixins: [
            GotoMixin,
            MetaMixin,
        ],
        data: function () {
            return {
                loading: false,
                photos: [],
                previousPage: null,
                currentPage: 1,
                nextPage: null,
                previousPageExists: false,
                nextPageExists: false,
            };
        },
        computed: {
            routeName: function () {
                const withPageSuffix = "-with-page";
                return this.$route.name.endsWith(withPageSuffix) ? this.$route.name : `${this.$route.name}${withPageSuffix}`;
            },
            pageTitle: function () {
                if (this.$route.params.search_phrase) {
                    return `Search "${this.$route.params.search_phrase}"`;
                }
                if (this.$route.params.tag) {
                    return `Search by tag #${this.$route.params.tag}`;
                }
                return "All photos";
            },
        },
        watch: {
            "$route": function () {
                this.init();
            },
            currentPage: function (currentPage) {
                if (currentPage > 1) {
                    this.$router.push({name: this.routeName, params: {page: currentPage}});
                }
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
            loadPhotos: async function () {
                this.loading = true;
                try {
                    const {items, previousPage, currentPage, nextPage, previousPageExists, nextPageExists} = await photoService.getPhotos({
                        page: this.$route.params.page,
                        tag: this.$route.params.tag,
                        searchPhrase: this.$route.params.search_phrase,
                    });
                    this.photos = items;
                    this.previousPage = previousPage;
                    this.currentPage = currentPage;
                    this.nextPage = nextPage;
                    this.previousPageExists = previousPageExists;
                    this.nextPageExists = nextPageExists;
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
