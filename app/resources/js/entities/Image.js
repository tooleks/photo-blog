export default class Image {
    /**
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
     * @returns {string}
     */
    valueOf() {
        return this.url;
    }

    /**
     * @returns {string}
     */
    toString() {
        return String(this.valueOf());
    }

    /**
     * @returns {Image}
     */
    clone() {
        return new Image({
            url: this.url,
            width: this.width,
            height: this.height,
        });
    }
}
