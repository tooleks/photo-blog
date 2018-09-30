import {mapActions, mapState} from "vuex";

export default {
    watch: {
        pageStatusCode: function (pageStatusCode) {
            document.head.querySelector("meta[name='prerender-status-code']").content = pageStatusCode;
        },
        pageName: function (pageName) {
            document.head.querySelector("meta[property='og:site_name']").content = pageName;
        },
        pageDescription: function (pageDescription) {
            document.head.querySelector("meta[name='description']").content = pageDescription;
            document.head.querySelector("meta[property='og:description']").content = pageDescription;
        },
        pageKeywords: function (pageKeywords) {
            document.head.querySelector("meta[name='keywords']").content = pageKeywords;
        },
        pageTitle: function (pageTitle) {
            document.title = pageTitle ? `${pageTitle} | ${this.pageName}` : this.pageName;
            document.head.querySelector("meta[property='og:title']").content = pageTitle;
            document.head.querySelector("meta[name='twitter:title']").content = pageTitle;
        },
        pageImage: function (pageImage) {
            document.head.querySelector("meta[property='og:image']").content = pageImage;
            document.head.querySelector("meta[name='twitter:image']").content = pageImage;
        },
        pageUrl: function (pageUrl) {
            document.head.querySelector("meta[property='og:url']").content = pageUrl;
        },
        pageCanonicalUrl: function (pageCanonicalUrl) {
            document.head.querySelector("link[rel='canonical']").href = pageCanonicalUrl;
        },
        "$route": function ($route) {
            let baseUrl = this.$services.getConfig().url.app;
            if ($route.fullPath) {
                this.setPageUrl(baseUrl + $route.fullPath);
                this.setPageCanonicalUrl(baseUrl + $route.path);
            }
        },
    },
    computed: mapState({
        pageStatusCode: (state) => state.meta.pageStatusCode,
        pageName: (state) => state.meta.pageName,
        pageDescription: (state) => state.meta.pageDescription,
        pageKeywords: (state) => state.meta.pageKeywords,
        pageTitle: (state) => state.meta.pageTitle,
        pageImage: (state) => state.meta.pageImage,
        pageUrl: (state) => state.meta.pageUrl,
        pageCanonicalUrl: (state) => state.meta.pageCanonicalUrl,
    }),
    methods: {
        init: function () {
            document.head.querySelector("meta[property='og:type']").content = "article";
            document.head.querySelector("meta[name='twitter:card']").content = "summary_large_image";
        },
        ...mapActions("meta", [
            "setPageStatusCode",
            "setPageName",
            "setPageDescription",
            "setPageKeywords",
            "setPageTitle",
            "setPageImage",
            "setPageUrl",
            "setPageCanonicalUrl",
        ]),
    },
    created: function () {
        this.init();
        this.setPageStatusCode(200);
        this.setPageName(this.$services.getConfig().app.name);
        this.setPageDescription(this.$services.getConfig().app.description);
        this.setPageKeywords(this.$services.getConfig().app.keywords);
        this.setPageKeywords(this.$services.getConfig().app.keywords);
        this.setPageImage(this.$services.getConfig().url.image);
    },
}
