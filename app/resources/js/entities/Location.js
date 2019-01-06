export default class Location {
    /**
     * @constructor
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
     * @return {number}
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
     * @return {number}
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
     * @return {string}
     */
    valueOf() {
        return `${this.lat.toFixed(4)}, ${this.lng.toFixed(4)}`;
    }

    /**
     * @return {string}
     */
    toString() {
        return String(this.valueOf());
    }

    /**
     * @return {Location}
     */
    clone() {
        return new Location({
            lat: this.lat,
            lng: this.lng,
        });
    }
}
