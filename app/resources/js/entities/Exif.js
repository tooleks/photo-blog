import moment from "moment";

export default class Exif {
    /**
     * Exif constructor.
     *
     * @param {Object} attributes
     * @param {string} attributes.manufacturer
     * @param {string} attributes.model
     * @param {string} attributes.exposureTime
     * @param {string} attributes.aperture
     * @param {string} attributes.iso
     * @param {string} attributes.takenAt
     */
    constructor({manufacturer, model, exposureTime, aperture, iso, takenAt}) {
        this.manufacturer = manufacturer;
        this.model = model;
        this.exposureTime = exposureTime;
        this.aperture = aperture;
        this.iso = iso;
        this.takenAt = takenAt;
    }

    /**
     * @param {string} value
     */
    set takenAt(value) {
        this._takenAt = value;
    }

    /**
     * @return {*}
     */
    get takenAt() {
        if (typeof this._takenAt === "undefined") {
            return undefined;
        }

        const takenAt = moment.utc(this._takenAt);

        if (!takenAt.isValid()) {
            return undefined;
        }

        return takenAt;
    }
}
