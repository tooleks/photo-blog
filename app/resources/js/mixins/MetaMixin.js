import {mapActions, mapState} from "vuex";
import getOrCreateHeadElement from "../utils/getOrCreateHeadElement";

export default {
    watch: {
        pageStatusCode(pageStatusCode) {
            getOrCreateHeadElement("meta", {name: "prerender-status-code"}).setAttribute("content", pageStatusCode);
        },
        pageName(pageName) {
            getOrCreateHeadElement("meta", {property: "og:site_name"}).setAttribute("content", pageName);
        },
        pageDescription(pageDescription) {
            getOrCreateHeadElement("meta", {name: "description"}).setAttribute("content", pageDescription);
            getOrCreateHeadElement("meta", {property: "og:description"}).setAttribute("content", pageDescription);
        },
        pageKeywords(pageKeywords) {
            getOrCreateHeadElement("meta", {name: "keywords"}).setAttribute("content", pageKeywords);
        },
        pageTitle(pageTitle) {
            document.title = pageTitle ? `${pageTitle} | ${this.pageName}` : this.pageName;
            getOrCreateHeadElement("meta", {property: "og:title"}).setAttribute("content", pageTitle);
            getOrCreateHeadElement("meta", {name: "twitter:title"}).setAttribute("content", pageTitle);
        },
        pageImage(pageImage) {
            getOrCreateHeadElement("meta", {property: "og:image"}).setAttribute("content", pageImage);
            getOrCreateHeadElement("meta", {name: "twitter:image"}).setAttribute("content", pageImage);
        },
        pageUrl(pageUrl) {
            getOrCreateHeadElement("meta", {property: "og:url"}).setAttribute("content", pageUrl);
        },
        pageCanonicalUrl(pageCanonicalUrl) {
            getOrCreateHeadElement("link", {rel: "canonical"}).setAttribute("href", pageCanonicalUrl);
        },
        ["$route"]() {
            const baseUrl = this.$services.getConfig().url.app;
            this.setPageUrl(baseUrl + this.$route.fullPath);
            this.setPageCanonicalUrl(baseUrl + this.$route.path);
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
    methods: mapActions("meta", [
        "setPageStatusCode",
        "setPageName",
        "setPageDescription",
        "setPageKeywords",
        "setPageTitle",
        "setPageImage",
        "setPageUrl",
        "setPageCanonicalUrl",
    ]),
    created() {
        getOrCreateHeadElement("meta", {property: "og:type"}).setAttribute("content", "article");
        getOrCreateHeadElement("meta", {name: "twitter:card"}).setAttribute("content", "summary_large_image");
        this.setPageStatusCode(200);
        this.setPageName(this.$services.getConfig().app.name);
        this.setPageDescription(this.$services.getConfig().app.description);
        this.setPageKeywords(this.$services.getConfig().app.keywords);
        this.setPageKeywords(this.$services.getConfig().app.keywords);
        this.setPageImage(this.$services.getConfig().url.image);
        const baseUrl = this.$services.getConfig().url.app;
        this.setPageUrl(baseUrl + this.$route.fullPath);
        this.setPageCanonicalUrl(baseUrl + this.$route.path);
    },
}
