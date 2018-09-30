import {_PAGINATION, HOME, PHOTO, PHOTOS, PHOTOS_SEARCH, PHOTOS_TAG, ROUTE_404, SIGN_IN} from "../router/names";

export default {
    computed: {
        routeName: function () {
            return this.$route.name.endsWith(_PAGINATION) ? this.$route.name : `${this.$route.name}${_PAGINATION}`;
        },
    },
    methods: {
        goToPath: function (path) {
            if (typeof path !== "undefined") {
                this.$router.push({path});
                return;
            }
            this.goToHomePage();
        },
        goToRedirectUri: function (redirectUri = this.$route.query.redirectUri) {
            this.goToPath(redirectUri);
        },
        goToHomePage: function () {
            this.$router.push({name: HOME});
        },
        goToSignInPage: function () {
            this.$router.push({name: SIGN_IN});
        },
        goToPhotoPage: function (id) {
            /*
             |--------------------------------------------------------------------------
             | Route Parameters
             |--------------------------------------------------------------------------
             */

            const params = {};

            params.id = id;

            /*
             |--------------------------------------------------------------------------
             | Search Query Parameters
             |--------------------------------------------------------------------------
             */

            const query = {};

            const page = this.$route.params.page || this.$route.query.page;
            if (typeof page !== "undefined") {
                query.page = page;
            }

            const tag = this.$route.params.tag || this.$route.query.tag;
            if (typeof tag !== "undefined") {
                query.tag = tag;
            }

            const searchPhrase = this.$route.params.searchPhrase || this.$route.query.searchPhrase;
            if (typeof searchPhrase !== "undefined") {
                query.searchPhrase = searchPhrase;
            }

            /*
             |--------------------------------------------------------------------------
             | Route Redirection
             |--------------------------------------------------------------------------
             */

            this.$router.push({name: PHOTO, params, query});
        },
        goToPhotosPage: function (id) {
            /*
             |--------------------------------------------------------------------------
             | Route Name
             |--------------------------------------------------------------------------
             */

            let name = PHOTOS;

            if (typeof this.$route.query.tag !== "undefined") {
                name = PHOTOS_TAG;
            }

            if (typeof this.$route.query.searchPhrase !== "undefined") {
                name = PHOTOS_SEARCH;
            }

            if (typeof this.$route.query.page !== "undefined") {
                name = name + _PAGINATION;
            }

            /*
             |--------------------------------------------------------------------------
             | Route Params
             |--------------------------------------------------------------------------
             */

            const params = this.$route.query;

            /*
             |--------------------------------------------------------------------------
             | Route Hash
             |--------------------------------------------------------------------------
             */

            let hash = this.$route.hash;

            if (typeof id !== "undefined") {
                hash = `#gallery-image-${id}`;
            }

            /*
             |--------------------------------------------------------------------------
             | Route Redirection
             |--------------------------------------------------------------------------
             */

            this.$router.push({name, params, hash});
        },
        goToNotFoundPage: function () {
            this.$router.push({name: ROUTE_404});
        },
    },
}
