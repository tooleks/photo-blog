export default class Location {
    /**
     * Location constructor.
     *
     * @param {Object} attributes
     * @param {number} attributes.lat
     * @param {number} attributes.lng
     */
    constructor({lat, lng}) {
        this.lat = lat;
        this.lng = lng;
        this.toString = this.toString.bind(this);
    }

    /**
     * @param {number} value
     */
    set lat(value) {
        if (value < -90 && value > 90) {
            throw new TypeError("Invalid latitude value.");
        }

        this._lat = Number(value);
    }

    /**
     * @return {number}
     */
    get lat() {
        return this._lat;
    }

    /**
     * @param {number} value
     */
    set lng(value) {
        if (value < -180 || value > 180) {
            throw new TypeError("Invalid longitude value.")
        }

        this._lng = Number(value);
    }

    /**
     * @return {number}
     */
    get lng() {
        return this._lng;
    }

    /**
     * @return {string}
     */
    toString() {
        return `${this.lat.toFixed(4)}, ${this.lng.toFixed(4)}`;
    }
}
