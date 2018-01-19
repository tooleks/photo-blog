<template>
    <div class="gallery-masonry">
        <div v-for="row in rows" class="gallery-row" :style="getRowStyle()">
            <div v-for="(image, index) of row" class="gallery-cell" :style="getCellStyle()">
                <div class="gallery-image" :style="getImageStyle(image, index, row)">
                    <router-link :to="image.getModel().route"
                                 :title="image.getModel().description"
                                 class="gallery-link"></router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
    .gallery-masonry {
    }

    .gallery-row {
    }

    .gallery-cell {
        float: left;
    }

    .gallery-cell:first-child {
        padding-left: 0 !important;
    }

    .gallery-cell:last-child {
        padding-right: 0 !important;;
    }

    .gallery-image {
        position: relative;
        background-size: cover;
    }

    .gallery-link {
        position: absolute;
        display: block;
        width: 100%;
        height: 100%;
    }
</style>

<script>
    import {Masonry} from "./core";

    export default {
        props: {
            images: {
                type: Array,
                default: () => {
                    return [];
                },
            },
            refreshInterval: {
                type: Number,
                default: 50,
            },
            rowMaxHeight: {
                type: Number,
                default: 300,
            },
            rowMaxWidth: {
                type: Number,
                default: 300,
            },
            cellPadding: {
                type: Number,
                default: 3,
                validator: function (value) {
                    return value <= 3;
                },
            },
        },
        data: function () {
            return {
                intervals: [],
                element: {
                    width: 0,
                    height: 0,
                },
                masonry: new Masonry({maxWidth: this.rowMaxWidth, maxHeight: this.rowMaxHeight}),
            };
        },
        computed: {
            rows: function () {
                return this.masonry.getRows();
            },
        },
        watch: {
            images: function (images) {
                this.masonry.reset();
                this.masonry.setOptions({
                    rowMaxWidth: this.element.width || this.rowMaxWidth,
                    rowMaxHeight: this.element.height || this.rowMaxHeight,
                });
                this.masonry.process(images);
            },
        },
        methods: {
            getRowStyle: function () {
                return {};
            },
            getCellStyle: function () {
                return {
                    "padding": `${this.cellPadding}px`,
                };
            },
            getImageStyle: function (image, index, row) {
                const isFirstOrLastImage = row.length && (index === 0 || index === row.length - 1);
                const width = image.getWidth() - this.cellPadding * (isFirstOrLastImage ? 1 : 2);
                const height = image.getHeight() - this.cellPadding * 2;
                return {
                    "background-image": `url(${image.getModel().thumbnail.url})`,
                    "background-color": image.getModel().averageColor,
                    "width": `${width}px`,
                    "height": `${height}px`,
                };
            },
        },
        created: function () {
            this.intervals.push(setInterval(() => {
                const width = parseInt(this.$el.offsetWidth);
                const height = parseInt(this.$el.offsetHeight);
                if (width !== this.element.width) {
                    // Save current `width` and `height` values for future checks.
                    this.element.width = width;
                    this.element.height = height;
                    // Reset masonry to new `maxWidth` option.
                    this.masonry.reset();
                    this.masonry.setOptions({rowMaxWidth: width, rowMaxHeight: this.rowMaxHeight});
                    this.masonry.process(this.images);
                }
            }, this.refreshInterval));
        },
        beforeDestroy: function () {
            this.intervals.forEach((interval) => clearInterval(interval));
        },
    }
</script>
