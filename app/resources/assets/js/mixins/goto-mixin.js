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
            this.$router.push({name: "home"});
        },
        goToSignInPage: function () {
            this.$router.push({name: "sign-in"});
        },
        goToPhotoPage: function (id) {
            this.$router.push({
                name: "photo",
                params: {id},
                query: {
                    tag: this.$route.params.tag || this.$route.query.tag,
                    search_phrase: this.$route.params.search_phrase || this.$route.query.search_phrase,
                    page: this.$route.params.page || this.$route.query.page,
                },
            });
        },
        goToPhotosPage: function () {
            const withPageSuffix = "-with-page";
            let name = "photos";
            if (this.$route.query.tag) {
                name = "photos-tag";
                if (this.$route.query.page) {
                    name = name + withPageSuffix;
                }
            } else if (this.$route.query.search_phrase) {
                name = "photos-search";
                if (this.$route.query.page) {
                    name = name + withPageSuffix;
                }
            } else {
                name = "photos";
                if (this.$route.query.page) {
                    name = name + withPageSuffix;
                }
            }
            this.$router.push({name, params: this.$route.query});
        },
        goTo404Page: function () {
            this.$router.push({name: "404"});
        },
    },
}
