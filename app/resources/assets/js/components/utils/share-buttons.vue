<template>
    <div class="btn-group">
        <a :href="getFacebookShareUrl()"
           target="_blank"
           class="btn"
           title="Share on Facebook"><i class="fa fa-facebook color-facebook" aria-hidden="true"></i></a>
        <a :href="getTwitterShareUrl()"
           target="_blank"
           class="btn"
           title="Share on Twitter"><i class="fa fa-twitter color-twitter" aria-hidden="true"></i></a>
        <a :href="getGooglePlusShareUrl()"
           target="_blank"
           class="btn"
           title="Share on Google+"><i class="fa fa-google-plus color-google-plus" aria-hidden="true"></i></a>
        <a :href="getLinkedInShareUrl()"
           target="_blank"
           class="btn"
           title="Share on LinkedIn"><i class="fa fa-linkedin color-linkedin" aria-hidden="true"></i></a>
    </div>
</template>

<style scoped>
    .color-facebook {
        color: #4867aa;
    }

    .color-twitter {
        color: #1da1f2;
    }

    .color-google-plus {
        color: #dd4f43;
    }

    .color-linkedin {
        color: #0077b5;
    }
</style>

<script>
    import config from "../../config";

    export default {
        computed: {
            url: function () {
                let url = config.url.app;
                if (this.$route.fullPath) {
                    url += this.$route.fullPath;
                }
                return encodeURIComponent(url);
            },
            title: function () {
                return document.title;
            },
        },
        methods: {
            getFacebookShareUrl: function () {
                return `https://www.facebook.com/sharer/sharer.php?u=${this.url}`;
            },
            getTwitterShareUrl: function () {
                return `https://twitter.com/home?status=${this.url}`;
            },
            getGooglePlusShareUrl: function () {
                return `https://plus.google.com/share?url=${this.url}`;
            },
            getLinkedInShareUrl: function () {
                return `https://www.linkedin.com/shareArticle?mini=true&url=${this.url}&title=${this.title}&summary=&source=`;
            },
        },
    }
</script>
