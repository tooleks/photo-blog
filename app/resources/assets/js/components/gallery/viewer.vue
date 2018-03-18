<template>
    <div :id="id"
         class="carousel slide bg-dark"
         data-interval="false"
         data-keyboard="true"
         data-ride="false"
         data-wrap="false"
         v-swipe-left="slideToNextImage"
         v-swipe-right="slideToPreviousImage">
        <div class="carousel-inner text-center">
            <div v-for="image in images" :key="image.id" class="carousel-item">
                <img class="carousel-image"
                     v-swipe-left="slideToNextImage"
                     v-swipe-right="slideToPreviousImage"
                     :src="image"
                     :alt="image.description"
                     :title="image.description">
            </div>
        </div>
        <button v-if="!isFirstImage(this.activeImage)"
                @click="slideToPreviousImage"
                class="carousel-control carousel-control-prev"
                role="button"
                title="Previous Image"
                aria-label="Previous Image">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </button>
        <button v-if="!isLastImage(this.activeImage)"
                @click="slideToNextImage"
                class="carousel-control carousel-control-next"
                role="button"
                title="Next Image"
                aria-label="Next Image">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </button>
    </div>
</template>

<style scoped>
    @media screen and (orientation: portrait) {
        .carousel-item {
            height: 40vh;
        }
    }

    @media screen and (orientation: landscape) {
        .carousel-item {
            height: 70vh;
        }
    }

    .carousel-image {
        width: auto;
        height: auto;
        max-width: 100%;
        max-height: 100%;
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        margin: auto;
    }

    .carousel-control {
        background: transparent;
        border: 0;
        cursor: pointer;
    }
</style>

<script>
    const KEY_CODE_ESC = 27;
    const KEY_CODE_LEFT = 37;
    const KEY_CODE_RIGHT = 39;

    export default {
        props: {
            id: {
                type: String,
                default: () => {
                    const id = Math.random().toString(36).substr(2, 5);
                    return `image-viewer-${id}`;
                },
            },
            activeImage: {
                type: Object,
                default: () => {
                    return {};
                },
            },
            images: {
                type: Array,
                default: () => {
                    return [];
                },
            },
        },
        computed: {
            carouselSelector: function () {
                return `#${this.id}`;
            },
            carouselItemSelector: function () {
                return `${this.carouselSelector} .carousel-item`;
            },
        },
        watch: {
            activeImage: function (activeImage) {
                // this.slideToImage(activeImage);
            },
        },
        methods: {
            init: function () {
                this.registerEventListeners();
                this.emitActiveImageEvents(this.activeImage);
                $(this.carouselItemSelector).first().addClass("active");
                $(this.carouselSelector).on("slide.bs.carousel", (event) => {
                    const activeImage = this.images[event.to];
                    this.emitActiveImageEvents(activeImage);
                });
            },
            reset: function () {
                this.resetEventListeners();
            },
            registerEventListeners: function () {
                window.addEventListener("keyup", this.handleKeyUpEvent);
            },
            resetEventListeners: function () {
                window.removeEventListener("keyup", this.handleKeyUpEvent);
            },
            handleKeyUpEvent: function (event) {
                switch (event.keyCode) {
                    case KEY_CODE_ESC: {
                        this.emitExitEvent();
                        break;
                    }
                    case KEY_CODE_LEFT: {
                        this.slideToPreviousImage();
                        break;
                    }
                    case KEY_CODE_RIGHT: {
                        this.slideToNextImage();
                        break;
                    }
                }
            },
            slideToPreviousImage: function () {
                $(this.carouselSelector).carousel("prev");
            },
            slideToNextImage: function () {
                $(this.carouselSelector).carousel("next");
            },
            slideToImage: function (activeImage) {
                const index = this.images.findIndex((image) => activeImage.is(image));
                if (index !== -1) {
                    $(this.carouselSelector).carousel(index);
                }
            },
            emitActiveImageEvents: function (activeImage) {
                this.$emit("update:activeImage", activeImage);
                if (this.isFirstImage(activeImage)) {
                    this.$emit("onFirstImage", activeImage);
                }
                if (this.isLastImage(activeImage)) {
                    this.$emit("onLastImage", activeImage);
                }
            },
            emitExitEvent: function () {
                this.$emit("onExit");
            },
            isFirstImage: function (activeImage) {
                const index = this.images.findIndex((image) => image.is(activeImage));
                return index === 0;
            },
            isLastImage: function (activeImage) {
                const index = this.images.findIndex((image) => image.is(activeImage));
                return index === this.images.length - 1;
            },
        },
        mounted: function () {
            this.init();
        },
        beforeDestroy: function () {
            this.reset();
        },
    }
</script>
