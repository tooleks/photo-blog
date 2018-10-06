<template>
    <div class="image-marker-container">
        <a :href="linkUrl" :title="title" :aria-label="title" target="_blank" class="image-marker box-shadow-2dp"
           :style="style"></a>
    </div>
</template>

<style scoped>
    .image-marker-container {
        position: relative;
        width: 100%;
        height: 100%;
    }

    .image-marker {
        position: absolute;
        top: -1.6em;
        left: -1.6em;
        display: block;
        width: 4em;
        height: 4em;
        border-radius: 50%;
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
        background-color: darkgrey;
        transition: 0.2s;
    }

    .image-marker:hover, .image-marker:focus {
        top: -2.4em;
        left: -2.4em;
        width: 6em;
        height: 6em;
    }
</style>

<script>
    export default {
        props: {
            imageUrl: {type: String},
            linkUrl: {type: String},
            title: {type: String},
        },
        computed: {
            style() {
                return {
                    "background-image": `url(${this.imageUrl})`,
                };
            },
        },
    };
</script>
