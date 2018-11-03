<template>
    <div class="btn-group">
        <a :href="getFacebookShareUrl()"
           target="_blank"
           class="btn"
           aria-label="Share on Facebook"
           title="Share on Facebook"><i class="fa fa-facebook color-facebook" aria-hidden="true"></i></a>
        <a :href="getTwitterShareUrl()"
           target="_blank"
           class="btn"
           aria-label="Share on Twitter"
           title="Share on Twitter"><i class="fa fa-twitter color-twitter" aria-hidden="true"></i></a>
        <a :href="getGooglePlusShareUrl()"
           target="_blank"
           class="btn"
           aria-label="Share on Google+"
           title="Share on Google+"><i class="fa fa-google-plus color-google-plus" aria-hidden="true"></i></a>
        <a :href="getLinkedInShareUrl()"
           target="_blank"
           class="btn"
           aria-label="Share on LinkedIn"
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
    export default {
        props: {
            url: {
                type: String,
                default() {
                    return this.$services.getConfig().url.app + this.$route.fullPath;
                },
            },
            title: {
                type: String,
                default() {
                    return document.title;
                },
            },
        },
        methods: {
            getFacebookShareUrl() {
                return `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(this.url)}`;
            },
            getTwitterShareUrl() {
                return `https://twitter.com/home?status=${encodeURIComponent(this.url)}`;
            },
            getGooglePlusShareUrl() {
                return `https://plus.google.com/share?url=${encodeURIComponent(this.url)}`;
            },
            getLinkedInShareUrl() {
                return `https://www.linkedin.com/shareArticle?mini=true&url=${encodeURIComponent(this.url)}`
                    + `&title=${encodeURIComponent(this.title)}&summary=&source=`;
            },
        },
    }
</script>
