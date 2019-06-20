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
        <button v-if="!isFirstImage(this.currentImage)"
                @click="slideToPreviousImage"
                class="carousel-control carousel-control-prev"
                role="button"
                :title="$lang('Previous')"
                :aria-label="$lang('Previous')">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">{{ $lang("Previous") }}</span>
        </button>
        <button v-if="!isLastImage(this.currentImage)"
                @click="slideToNextImage"
                class="carousel-control carousel-control-next"
                role="button"
                :title="$lang('Next')"
                :aria-label="$lang('Next')">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">{{ $lang("Next") }}</span>
        </button>
        <div class="carousel-actions">
            <button @click="toggleFullScreenMode"
                    class="carousel-action"
                    type="button"
                    :aria-label="$lang('Toggle full screen mode')"
                    :title="$lang('Toggle full screen mode')">
                <i class="fa"
                   :class="{'fa-expand': !inFullScreenMode, 'fa-close': inFullScreenMode}"
                   aria-hidden="true"></i>
            </button>
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
            z-index: 1;

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
                default() {
                    const id = Math.random().toString(36).substr(2, 5);
                    return `image-viewer-${id}`;
                },
            },
            currentImage: {
                type: Object,
                default() {
                    return {};
                },
            },
            images: {
                type: Array,
                default() {
                    return [];
                },
            },
        },
        data() {
            return {
                /** @type {boolean} */
                inFullScreenMode: false,
            };
        },
        computed: {
            carouselSelector() {
                return `#${this.id}`;
            },
            carouselItemSelector() {
                return `${this.carouselSelector} .carousel-item`;
            },
        },
        watch: {
            currentImage(currentImage) {
                // this.slideToImage(currentImage);
            },
        },
        methods: {
            init() {
                this.emitCurrentImageEvents(this.currentImage);
                $(this.carouselItemSelector).first().addClass("active");
                $(this.carouselSelector).on("slide.bs.carousel", (event) => {
                    const currentImage = this.images[event.to];
                    this.emitCurrentImageEvents(currentImage);
                });
            },
            onKeyUp(event) {
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
            slideToPreviousImage() {
                $(this.carouselSelector).carousel("prev");
            },
            slideToNextImage() {
                $(this.carouselSelector).carousel("next");
            },
            slideToImage(currentImage) {
                const index = this.images.findIndex((image) => currentImage.equals(image));
                if (index !== -1) {
                    $(this.carouselSelector).carousel(index);
                }
            },
            emitCurrentImageEvents(currentImage) {
                this.$emit("update:currentImage", currentImage);
                if (this.isFirstImage(currentImage)) {
                    this.$emit("onFirstImage", currentImage);
                }
                if (this.isLastImage(currentImage)) {
                    this.$emit("onLastImage", currentImage);
                }
            },
            emitExitEvent() {
                this.$emit("onExit");
            },
            isFirstImage(currentImage) {
                const index = this.images.findIndex((image) => image.equals(currentImage));
                return index === 0;
            },
            isLastImage(currentImage) {
                const index = this.images.findIndex((image) => image.equals(currentImage));
                return index === this.images.length - 1;
            },
            toggleFullScreenMode() {
                this.inFullScreenMode = !this.inFullScreenMode;
            },
        },
        mounted() {
            this.init();
        },
    }
</script>
