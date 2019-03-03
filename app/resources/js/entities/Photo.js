import {cloneDeep} from "lodash";
import router from "../router";
import {routeName} from "../router/identifiers";

export default class Photo {
    /**
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
        this.clone = this.clone.bind(this);
        this.equals = this.equals.bind(this);
        this.replaceImage = this.replaceImage.bind(this);
    }

    /**
     * Warning! This getter definitely shouldn't be here.
     *
     * @returns {Object}
     */
    get route() {
        const route = router.history.current;
        return cloneDeep({
            name: routeName.photo,
            params: {
                id: this.postId,
            },
            query: {
                tag: route.params.tag || route.query.tag,
                searchPhrase: route.params.searchPhrase || route.query.searchPhrase,
            },
        });
    }

    /**
     * @returns {boolean}
     */
    get published() {
        return Boolean(this.postId);
    }

    /**
     * @returns {Image}
     */
    get image() {
        return this.original;
    }

    /**
     * @returns {string}
     */
    valueOf() {
        return this.original.toString();
    }

    /**
     * @returns {string}
     */
    toString() {
        return String(this.valueOf());
    }

    /**
     * @returns {Photo}
     */
    clone() {
        return new Photo({
            postId: this.postId,
            id: this.id,
            description: this.description,
            tags: this.tags.map((tag) => tag.clone()),
            original: this.original.clone(),
            thumbnail: this.thumbnail.clone(),
            exif: this.exif.clone(),
            averageColor: this.averageColor,
            location: this.location.clone(),
        });
    }

    /**
     * @param {Photo} photo
     * @returns {boolean}
     */
    equals(photo) {
        return this.id === photo.id;
    }

    /**
     * @param {Photo} photo
     * @returns {Photo}
     */
    replaceImage(photo) {
        this.id = photo.id;
        this.original = photo.original.clone();
        this.thumbnail = photo.thumbnail.clone();
        this.exif = photo.exif.clone();
        this.averageColor = photo.averageColor;
        this.location = photo.location.clone();
        return this;
    }
}
