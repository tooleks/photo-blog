import router from "../router";

export default class Photo {
    /**
     * Photo constructor.
     *
     * @param {Object} attributes
     * @param {number} attributes.postId
     * @param {number} attributes.id
     * @param {string} [attributes.description]
     * @param {Array<Tag>} [attributes.tags]
     * @param {Image} attributes.original
     * @param {Image} attributes.thumbnail
     * @param {Exif} attributes.exif
     * @param {string} attributes.averageColor
     * @param {Location} [attributes.location]
     */
    constructor({postId, id, description = "", tags = [], original, thumbnail, exif, averageColor, location}) {
        this.postId = postId;
        this.id = id;
        this.description = description;
        this.tags = tags;
        this.original = original;
        this.thumbnail = thumbnail;
        this.exif = exif;
        this.averageColor = averageColor;
        this.location = location;
        this.valueOf = this.valueOf.bind(this);
        this.toString = this.toString.bind(this);
        this.is = this.is.bind(this);
    }

    /**
     * Warning! This getter definitely shouldn't be here.
     *
     * @return {Object}
     */
    get route() {
        const route = router.history.current;
        return {
            name: "photo",
            params: {
                id: this.postId,
            },
            query: {
                tag: route.params.tag || route.query.tag,
                searchPhrase: route.params.searchPhrase || route.query.searchPhrase,
                page: route.params.page || route.query.page,
            },
        };
    }

    /**
     * @return {boolean}
     */
    get published() {
        return Boolean(this.postId);
    }

    /**
     * @return {Image}
     */
    get image() {
        return this.original;
    }

    /**
     * @return {string}
     */
    valueOf() {
        return this.original.toString();
    }

    /**
     * @return {string}
     */
    toString() {
        return String(this.valueOf());
    }

    /**
     * @param {Photo} photo
     * @return {boolean}
     */
    is(photo) {
        return this.id === photo.id;
    }

    /**
     * @param {Photo} photo
     * @return {Photo}
     */
    replaceImage(photo) {
        this.id = photo.id;
        this.original = photo.original;
        this.thumbnail = photo.thumbnail;
        this.exif = photo.exif;
        this.averageColor = photo.averageColor;
        this.location = photo.location;
        return this;
    }
}
