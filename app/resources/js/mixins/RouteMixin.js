import * as route from "../router/names";

export default {
    methods: {
        goToPath(path) {
            // If no path was provided go to the home page by default.
            if (!path) {
                this.goToHomePage();
                return;
            }

            this.$router.push({path});
        },
        goToRedirectUri() {
            this.goToPath(this.$route.query.redirectUri);
        },
        goToHomePage() {
            this.$router.push({name: route.home});
        },
        goToNotFoundPage() {
            this.$router.push({name: route.route404});
        },
        goToSignInPage() {
            this.$router.push({name: route.signIn});
        },
        goToPhotoPage(id, args = {...this.$route.params, ...this.$route.query}) {
            const query = {};

            if (typeof args.searchPhrase !== "undefined") {
                query.searchPhrase = args.searchPhrase;
            } else if (typeof args.tag !== "undefined") {
                query.tag = args.tag;
            }

            if (typeof args.page !== "undefined") {
                query.page = args.page;
            }

            this.$router.replace({
                name: route.photo,
                params: {
                    id,
                },
                query,
            });
        },
        goToPhotosPage(args = {...this.$route.params, ...this.$route.query}) {
            let name, hash;
            const params = {};

            // Initialize the route name and default route params.
            if (typeof args.searchPhrase !== "undefined") {
                name = route.photosSearch;
                params.searchPhrase = args.searchPhrase;
            } else if (typeof args.tag !== "undefined") {
                name = route.photosTag;
                params.tag = args.tag;
            } else {
                name = route.photos;
            }

            // Initialize pagination if the page number is provided.
            if (typeof args.page !== "undefined") {
                params.page = args.page;
            }

            this.$router.push({name, params, hash});
        },
    },
}
