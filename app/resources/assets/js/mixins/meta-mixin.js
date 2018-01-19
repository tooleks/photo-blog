import config from "../config";

export default {
    computed: {
        pageName: function () {
            return config.app.name;
        },
        pageDescription: function () {
            return config.app.description;
        },
        pageKeywords: function () {
            return config.app.keywords;
        },
        pageTitle: function () {
            return undefined;
        },
        pageImage: function () {
            return config.url.image;
        },
    },
    watch: {
        pageName: function () {
            this.initMeta();
        },
        pageDescription: function () {
            this.initMeta();
        },
        pageKeywords: function () {
            this.initMeta();
        },
        pageTitle: function () {
            this.initMeta();
        },
        pageImage: function () {
            this.initMeta();
        },
    },
    methods: {
        initMeta: function () {
            this.$store.commit("meta/setName", {name: this.pageName});
            this.$store.commit("meta/setDescription", {description: this.pageDescription});
            this.$store.commit("meta/setKeywords", {name: this.pageKeywords});
            this.$store.commit("meta/setTitle", {title: this.pageTitle});
            this.$store.commit("meta/setImage", {image: this.pageImage});
        },
    },
    created: function () {
        this.initMeta();
    },
    metaInfo: function () {
        return this.$store.getters["meta/getVueMetaInfo"];
    },
}
