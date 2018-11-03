<template>
    <div class="gallery-masonry">
        <div v-for="row in rows" class="gallery-row">
            <div v-for="(image, index) of row" class="gallery-cell" :style="getCellStyle()">
                <div :id="getImageId(image)"
                     class="gallery-image"
                     :style="getImageStyle(image, index, row)"
                     role="img"
                     :aria-label="image.model.description">
                    <router-link :to="getImagePageRoute(image)"
                                 :title="image.model.description"
                                 :aria-label="image.model.description"
                                 class="gallery-link"/>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
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
    import {cloneDeep} from "lodash";
    import waitUntil from "../../utils/waitUntil";
    import Masonry from "./core/Masonry";

    export default {
        props: {
            images: {
                type: Array,
                default() {
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
                validator(value) {
                    return value <= 3;
                },
            },
        },
        data() {
            return {
                /** @type {Array<Function>} */
                intervals: [],
                /** @type {Object} */
                element: {
                    width: 0,
                    height: 0,
                },
                /** @type {Masonry} */
                masonry: new Masonry({maxWidth: this.rowMaxWidth, maxHeight: this.rowMaxHeight}),
            };
        },
        computed: {
            rows() {
                return this.masonry.getRows();
            },
        },
        watch: {
            images(images) {
                this.masonry.reset();
                this.masonry.setOptions({
                    rowMaxWidth: this.element.width || this.rowMaxWidth,
                    rowMaxHeight: this.element.height || this.rowMaxHeight,
                });
                this.masonry.process(images);
            },
        },
        methods: {
            getCellStyle() {
                return {
                    "padding": `${this.cellPadding}px`,
                };
            },
            getImageId(image) {
                return `img-${image.model.id}`;
            },
            getImageStyle(image, index, row) {
                const isFirstOrLastImage = row.length && (index === 0 || index === row.length - 1);
                const width = image.width - this.cellPadding * (isFirstOrLastImage ? 1 : 2);
                const height = image.height - this.cellPadding * 2;
                return {
                    "background-image": `url(${image.model.thumbnail.url})`,
                    "background-color": image.model.averageColor,
                    "width": `${width}px`,
                    "height": `${height}px`,
                };
            },
            getImagePageRoute(image) {
                const route = cloneDeep(image.model.route);
                // Get the full path of the current route ignoring the hash segment.
                const [fullPath] = this.$route.fullPath.split("#");
                route.query.backUrl = `${fullPath}#${this.getImageId(image)}`;
                return route;
            },
            async scrollToImage() {
                if (this.$route.hash) {
                    const element = await waitUntil(() => document.querySelector(this.$route.hash));
                    element.scrollIntoView({behavior: "instant", block: "center"});
                }
            },
        },
        created() {
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
        beforeDestroy() {
            this.intervals.forEach((interval) => clearInterval(interval));
        },
    }
</script>
