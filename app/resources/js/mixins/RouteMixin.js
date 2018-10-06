import {pickBy} from "lodash";
import * as routeName from "../router/names";
import getEmptyRoute from "../router/getEmptyRoute";

export default {
    methods: {
        goToPath(path) {
            // If no path was provided go to the home page by default.
            if (!path) {
                this.goToHomePage();
                return;
            }

            const route = getEmptyRoute();
            route.path = path;
            this.$router.push(route);
        },
        goToRedirectUri() {
            this.goToPath(this.$route.query.redirectUri);
        },
        goToBackUri() {
            this.goToPath(this.$route.query.backUri);
        },
        goToHomePage() {
            const route = getEmptyRoute();
            route.name = routeName.home;
            this.$router.push(route);
        },
        goToNotFoundPage() {
            const route = getEmptyRoute();
            route.name = routeName.route404;
            this.$router.push(route);
        },
        goToSignInPage() {
            const route = getEmptyRoute();
            route.name = routeName.signIn;
            this.$router.push(route);
        },
        goToPhotoPage(id, args = {...this.$route.params, ...this.$route.query}) {
            const route = getEmptyRoute();
            route.name = routeName.photo;
            route.params.id = id;
            route.query = pickBy(args, (value, key) => {
                const allowedKey = ["searchPhrase", "tag", "page", "backUri"].indexOf(key) !== -1;
                const allowedValue = typeof value !== "undefined";
                return allowedKey && allowedValue;
            });
            this.$router.replace(route);
        },
        goToPhotosPage(args = {...this.$route.params, ...this.$route.query}) {
            const route = getEmptyRoute();
            route.params = pickBy(args, (value, key) => {
                const allowedKey = ["searchPhrase", "tag", "page"].indexOf(key) !== -1;
                const allowedValue = typeof value !== "undefined";
                return allowedKey && allowedValue;
            });
            if (typeof route.params.searchPhrase !== "undefined") {
                route.name = routeName.photosSearch;
            } else if (typeof route.params.tag !== "undefined") {
                route.name = routeName.photosTag;
            } else {
                route.name = routeName.photos;
            }
            this.$router.push(route);
        },
    },
}
