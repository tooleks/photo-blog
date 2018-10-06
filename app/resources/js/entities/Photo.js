import {cloneDeep} from "lodash";
import router from "../router";
import * as routeName from "../router/names";

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
        this.clone = this.clone.bind(this);
        this.is = this.is.bind(this);
        this.replaceImage = this.replaceImage.bind(this);
    }

    /**
     * Warning! This getter definitely shouldn't be here.
     *
     * @return {Object}
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
     * @return {Photo}
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
        this.original = photo.original.clone();
        this.thumbnail = photo.thumbnail.clone();
        this.exif = photo.exif.clone();
        this.averageColor = photo.averageColor;
        this.location = photo.location.clone();
        return this;
    }
}
