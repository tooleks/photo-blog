export default class Location {
    /**
     * @param {Object} attributes
     * @param {number} attributes.lat
     * @param {number} attributes.lng
     */
    constructor({lat, lng}) {
        this.lat = lat;
        this.lng = lng;
        this.valueOf = this.valueOf.bind(this);
        this.toString = this.toString.bind(this);
        this.clone = this.clone.bind(this);
    }

    /**
     * @returns {number}
     */
    get lat() {
        return this._lat;
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
     * @returns {number}
     */
    get lng() {
        return this._lng;
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
     * @returns {string}
     */
    valueOf() {
        return `${this.lat.toFixed(4)}, ${this.lng.toFixed(4)}`;
    }

    /**
     * @returns {string}
     */
    toString() {
        return String(this.valueOf());
    }

    /**
     * @returns {Location}
     */
    clone() {
        return new Location({
            lat: this.lat,
            lng: this.lng,
        });
    }
}
