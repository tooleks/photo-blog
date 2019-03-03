/* global document */

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
    computed: {
        pageStatusCode() {
            return this.$services.getMeta().statusCode;
        },
        pageName() {
            return this.$services.getMeta().name;
        },
        pageDescription() {
            return this.$services.getMeta().description;
        },
        pageKeywords() {
            return this.$services.getMeta().keywords;
        },
        pageTitle() {
            return this.$services.getMeta().title;
        },
        pageImage() {
            return this.$services.getMeta().image;
        },
        pageUrl() {
            return this.$services.getMeta().url;
        },
        pageCanonicalUrl() {
            return this.$services.getMeta().canonicalUrl;
        },
    },
    methods: {
        setPageStatusCode(statusCode) {
            this.$services.getMeta().statusCode = statusCode;
        },
        setPageName(name) {
            this.$services.getMeta().name = name;
        },
        setPageDescription(description) {
            this.$services.getMeta().description = description;
        },
        setPageKeywords(keywords) {
            this.$services.getMeta().keywords = keywords;
        },
        setPageTitle(title) {
            this.$services.getMeta().title = title;
        },
        setPageImage(image) {
            this.$services.getMeta().image = image;
        },
        setPageUrl(url) {
            this.$services.getMeta().url = url;
        },
        setPageCanonicalUrl(canonicalUrl) {
            this.$services.getMeta().canonicalUrl = canonicalUrl;
        },
    },
    created() {
        getOrCreateHeadElement("meta", {property: "og:type"}).setAttribute("content", "article");
        getOrCreateHeadElement("meta", {name: "twitter:card"}).setAttribute("content", "summary_large_image");
        this.setPageStatusCode("200");
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
