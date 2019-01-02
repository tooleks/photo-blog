export default class Image {
    /**
     * @constructor
     * @param {Object} attributes
     * @param {string} attributes.url
     * @param {number} attributes.width
     * @param {number} attributes.height
     */
    constructor({url, width, height}) {
        this.url = url;
        this.width = Number(width);
        this.height = Number(height);
        this.valueOf = this.valueOf.bind(this);
        this.toString = this.toString.bind(this);
        this.clone = this.clone.bind(this);
    }

    /**
     * @return {string}
     */
    valueOf() {
        return this.url;
    }

    /**
     * @return {string}
     */
    toString() {
        return String(this.valueOf());
    }

    /**
     * @return {Image}
     */
    clone() {
        return new Image({
            url: this.url,
            width: this.width,
            height: this.height,
        });
    }
}
