import {isDefined} from "tooleks";
import {_PAGINATION, HOME, PHOTO, PHOTOS, PHOTOS_SEARCH, PHOTOS_TAG, ROUTE_404, SIGN_IN} from "../router/names";
import {removeUndefinedKeys} from "../utils";

export default {
    methods: {
        goToPath: function (path) {
            if (path) {
                this.$router.push({path});
            } else {
                this.goToHomePage();
            }
        },
        goToHomePage: function () {
            this.$router.push({name: HOME});
        },
        goToSignInPage: function () {
            this.$router.push({name: SIGN_IN});
        },
        goToPhotoPage: function (id) {
            // Initialize the route params.
            const params = {id};
            removeUndefinedKeys(params);

            // Initialize the route query.
            const query = {
                tag: this.$route.params.tag || this.$route.query.tag,
                search_phrase: this.$route.params.search_phrase || this.$route.query.search_phrase,
                page: this.$route.params.page || this.$route.query.page,
            };
            removeUndefinedKeys(query);

            this.$router.push({name: PHOTO, params, query});
        },
        goToPhotosPage: function (id) {
            // Initialize the route name.
            let name = PHOTOS;
            // If tag query parameter exists, go to the tag page.
            if (isDefined(this.$route.query.tag)) {
                name = PHOTOS_TAG;
            }
            // If search phrase parameter exists go to the search page.
            if (isDefined(this.$route.query.search_phrase)) {
                name = PHOTOS_SEARCH;
            }
            // Modify the route name if the route supports paging.
            if (isDefined(this.$route.query.page)) {
                name = name + _PAGINATION;
            }

            // Initialize route params.
            const params = this.$route.query;
            removeUndefinedKeys(params);

            // Initialize the route hash.
            let hash = this.$route.hash;
            if (id) {
                hash = `#gallery-image-${id}`;
            }

            this.$router.push({name, params, hash});
        },
        goToNotFoundPage: function () {
            this.$router.push({name: ROUTE_404});
        },
    },
}
