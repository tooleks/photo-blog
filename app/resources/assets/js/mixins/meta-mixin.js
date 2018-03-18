import config from "../config";
import {optional} from "../utils";

export default {
    computed: {
        pageName: function () {
            return optional(() => config.app.name, "");
        },
        pageDescription: function () {
            return optional(() => config.app.description, "");
        },
        pageKeywords: function () {
            return optional(() => config.app.keywords, "");
        },
        pageTitle: function () {
            return "";
        },
        pageImage: function () {
            return optional(() => config.url.image, "");
        },
    },
    metaInfo: function () {
        return {
            title: this.pageTitle,
            titleTemplate: this.pageTitle ? `%s | ${this.pageName}` : this.pageName,
            meta: [
                {vmid: "description", name: "description", content: this.pageDescription},
                {vmid: "keywords", name: "keywords", content: this.pageKeywords},
                // Open Graph protocol properties.
                {vmid: "og:type", property: "og:type", content: "article"},
                {vmid: "og:url", property: "og:url", content: config.url.app + this.$route.fullPath},
                {vmid: "og:site_name", property: "og:site_name", content: this.pageName},
                {vmid: "og:description", property: "og:description", content: this.pageDescription},
                {vmid: "og:image", property: "og:image", content: this.pageImage},
                {vmid: "og:title", property: "og:title", content: this.pageTitle},
                // Twitter Cards properties.
                {vmid: "twitter:card", name: "twitter:card", content: "summary_large_image"},
                {vmid: "twitter:title", name: "twitter:title", content: this.pageTitle},
                {vmid: "twitter:image", name: "twitter:image", content: this.pageImage},
            ],
        };
    },
}
