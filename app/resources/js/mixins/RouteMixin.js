import _, {includes, isUndefined, negate} from "lodash";
import {routeName} from "../router/identifiers";

export default {
    methods: {
        goToPath(path = null) {
            if (path !== null) {
                this.$router.push({path});
                return;
            }

            // If no path was provided go to the home page by default.
            this.goToHomePage();
        },
        goToRedirectUrl() {
            this.goToPath(this.$route.query.redirectUrl);
        },
        goToBackUrl() {
            this.goToPath(this.$route.query.backUrl);
        },
        goToHomePage() {
            this.$router.push({name: routeName.home});
        },
        goToNotFoundPage() {
            this.$router.push({name: routeName.route404});
        },
        goToSignInPage() {
            this.$router.push({name: routeName.signIn});
        },
        goToPhotoPage(id, args = {...this.$route.params, ...this.$route.query}) {
            let name, params = {}, query = {};

            name = routeName.photo;
            params.id = id;

            // Omit junk query parameters.
            query = _(args)
                .pickBy(negate(isUndefined))
                .pickBy((value, key) => includes(["searchPhrase", "tag", "page", "backUrl"], key))
                .value();

            this.$router.replace({name, params, query});
        },
        goToPhotosPage(args = {...this.$route.params, ...this.$route.query}) {
            let name, params = {};

            // Omit junk route parameters.
            params = _(args)
                .pickBy(negate(isUndefined))
                .pickBy((value, key) => includes(["searchPhrase", "tag", "page"], key))
                .value();

            // Choose the right route name based on available route parameters.
            if (typeof params.searchPhrase !== "undefined") {
                name = routeName.photosSearch;
            } else if (typeof params.tag !== "undefined") {
                name = routeName.photosTag;
            } else {
                name = routeName.photos;
            }

            this.$router.push({name, params});
        },
    },
}
