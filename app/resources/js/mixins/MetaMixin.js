import {mapActions, mapState} from "vuex";

/**
 * @param {string} tag
 * @param {Object} attributes
 * @return {Node}
 */
export function getElement(tag, attributes) {
    // Find the element in the head tag.
    const elements = document.head.querySelectorAll(tag);
    let element = Array.from(elements).find((element) => {
        return Object.keys(attributes).every((key) => attributes[key] === element.getAttribute(key));
    });

    // If the element doesn't exist create a new one.
    if (!element) {
        element = document.createElement(tag);
        Object.keys(attributes).forEach((key) => element.setAttribute(key, attributes[key]));
        document.head.appendChild(element);
    }

    return element;
}

export default {
    watch: {
        pageStatusCode: function (pageStatusCode) {
            getElement("meta", {name: "prerender-status-code"}).setAttribute("content", pageStatusCode);
        },
        pageName: function (pageName) {
            getElement("meta", {property: "og:site_name"}).setAttribute("content", pageName);
        },
        pageDescription: function (pageDescription) {
            getElement("meta", {name: "description"}).setAttribute("content", pageDescription);
            getElement("meta", {property: "og:description"}).setAttribute("content", pageDescription);
        },
        pageKeywords: function (pageKeywords) {
            getElement("meta", {name: "keywords"}).setAttribute("content", pageKeywords);
        },
        pageTitle: function (pageTitle) {
            document.title = pageTitle ? `${pageTitle} | ${this.pageName}` : this.pageName;
            getElement("meta", {property: "og:title"}).setAttribute("content", pageTitle);
            getElement("meta", {name: "twitter:title"}).setAttribute("content", pageTitle);
        },
        pageImage: function (pageImage) {
            getElement("meta", {property: "og:image"}).setAttribute("content", pageImage);
            getElement("meta", {name: "twitter:image"}).setAttribute("content", pageImage);
        },
        pageUrl: function (pageUrl) {
            getElement("meta", {property: "og:url"}).setAttribute("content", pageUrl);
        },
        pageCanonicalUrl: function (pageCanonicalUrl) {
            getElement("link", {rel: "canonical"}).setAttribute("href", pageCanonicalUrl);
        },
        "$route": function () {
            this.syncPageUrl();
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
        syncPageUrl() {
            let baseUrl = this.$services.getConfig().url.app;
            this.setPageUrl(baseUrl + this.$route.fullPath);
            this.setPageCanonicalUrl(baseUrl + this.$route.path);
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
        getElement("meta", {property: "og:type"}).setAttribute("content", "article");
        getElement("meta", {name: "twitter:card"}).setAttribute("content", "summary_large_image");
        this.setPageStatusCode(200);
        this.setPageName(this.$services.getConfig().app.name);
        this.setPageDescription(this.$services.getConfig().app.description);
        this.setPageKeywords(this.$services.getConfig().app.keywords);
        this.setPageKeywords(this.$services.getConfig().app.keywords);
        this.setPageImage(this.$services.getConfig().url.image);
        this.syncPageUrl();
    },
}
