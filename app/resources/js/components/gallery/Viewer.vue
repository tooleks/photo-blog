<template>
    <div :id="id"
         class="carousel slide bg-dark"
         data-interval="false"
         data-keyboard="true"
         data-ride="false"
         data-wrap="false"
         :class="{'full-screen': inFullScreenMode}"
         v-on-swipe-left="slideToNextImage"
         v-on-swipe-right="slideToPreviousImage">
        <div class="carousel-inner text-center">
            <div v-for="image in images" :key="image.id" class="carousel-item">
                <img class="carousel-image"
                     v-on-swipe-left="slideToNextImage"
                     v-on-swipe-right="slideToPreviousImage"
                     v-on-key-up="onKeyUp"
                     :src="image"
                     :alt="image.description"
                     :title="image.description">
            </div>
        </div>
        <button v-if="!isFirstImage(this.activeImage)"
                @click="slideToPreviousImage"
                class="carousel-control carousel-control-prev"
                role="button"
                title="Previous"
                aria-label="Previous">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </button>
        <button v-if="!isLastImage(this.activeImage)"
                @click="slideToNextImage"
                class="carousel-control carousel-control-next"
                role="button"
                title="Next"
                aria-label="Next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </button>
        <div class="carousel-actions">
            <button @click="toggleFullScreenMode"
                    class="carousel-action"
                    type="button"
                    aria-label="Toggle full screen mode"
                    title="Toggle full screen mode"><i class="fa"
                                                       :class="{'fa-expand': !inFullScreenMode, 'fa-close': inFullScreenMode}"
                                                       aria-hidden="true"></i></button>
        </div>
    </div>
</template>

<style lang="scss" scoped>
    .carousel {
        &.full-screen {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 100000;
        }

        &:not(.full-screen) {
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
        }

        &.full-screen {
            .carousel-item {
                height: 100vh;
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
            width: auto;
            border: 0;
            background: transparent;
            cursor: pointer;
        }

        .carousel-control-prev {
            padding-right: 2.8em;
            padding-left: 1.4em;
        }

        .carousel-control-next {
            padding-right: 1.4em;
            padding-left: 2.8em;
        }

        .carousel-actions {
            position: absolute;
            top: 0;
            right: 0;

            .carousel-action {
                background: transparent;
                border: 0;
                padding: 0.6em 1em;
                font-size: 1.5em;
                cursor: pointer;
                color: #999c9f;

                &:hover, &:focus {
                    color: #eaebeb;
                }
            }
        }
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
                default: function () {
                    return [];
                },
            },
        },
        data: function () {
            return {
                /** @type {boolean} */
                inFullScreenMode: false,
            };
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
                this.emitActiveImageEvents(this.activeImage);
                $(this.carouselItemSelector).first().addClass("active");
                $(this.carouselSelector).on("slide.bs.carousel", (event) => {
                    const activeImage = this.images[event.to];
                    this.emitActiveImageEvents(activeImage);
                });
            },
            onKeyUp: function (event) {
                switch (event.keyCode) {
                    case KEY_CODE_ESC: {
                        if (this.inFullScreenMode) {
                            this.toggleFullScreenMode();
                        } else {
                            this.emitExitEvent();
                        }
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
            toggleFullScreenMode: function () {
                this.inFullScreenMode = !this.inFullScreenMode;
            },
        },
        mounted: function () {
            this.init();
        },
    }
</script>
