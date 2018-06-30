import {optional as opt} from "tooleks";

export default {
    computed: {
        pageStatusCode: function () {
            return 200;
        },
        pageName: function () {
            return opt(() => this.$dc.get("config").app.name, "");
        },
        pageDescription: function () {
            return opt(() => this.$dc.get("config").app.description, "");
        },
        pageKeywords: function () {
            return opt(() => this.$dc.get("config").app.keywords, "");
        },
        pageTitle: function () {
            return "";
        },
        pageImage: function () {
            return opt(() => this.$dc.get("config").url.image, "");
        },
        pageCanonicalUrl: function () {
            let url = this.$dc.get("config").url.app;
            if (this.$route.fullPath) {
                url += this.$route.path;
            }
            return url;
        },
    },
    metaInfo: function () {
        return {
            title: this.pageTitle,
            titleTemplate: this.pageTitle ? `%s | ${this.pageName}` : this.pageName,
            meta: [
                {vmid: "prerender-status-code", name: "prerender-status-code", content: this.pageStatusCode},
                //
                {vmid: "description", name: "description", content: this.pageDescription},
                {vmid: "keywords", name: "keywords", content: this.pageKeywords},
                // Open Graph protocol properties.
                {vmid: "og:type", property: "og:type", content: "article"},
                {vmid: "og:url", property: "og:url", content: this.$dc.get("config").url.app + this.$route.fullPath},
                {vmid: "og:site_name", property: "og:site_name", content: this.pageName},
                {vmid: "og:description", property: "og:description", content: this.pageDescription},
                {vmid: "og:image", property: "og:image", content: this.pageImage},
                {vmid: "og:title", property: "og:title", content: this.pageTitle},
                // Twitter Cards properties.
                {vmid: "twitter:card", name: "twitter:card", content: "summary_large_image"},
                {vmid: "twitter:title", name: "twitter:title", content: this.pageTitle},
                {vmid: "twitter:image", name: "twitter:image", content: this.pageImage},
            ],
            link: [
                {vmid: "canonical", rel: "canonical", href: this.pageCanonicalUrl},
            ],
        };
    },
}
