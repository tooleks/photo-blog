export default class Image {
    /**
     * Image constructor.
     *
     * @param {Object} attributes
     * @param {string} attributes.url
     * @param {number} attributes.width
     * @param {number} attributes.height
     */
    constructor({url, width, height}) {
        this.url = url;
        this.width = Number(width);
        this.height = Number(height);
    }

    /**
     * @return {string}
     */
    toString() {
        return String(this.url);
    }
}
