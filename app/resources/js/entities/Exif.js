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
     * @param {string} attributes.software
     */
    constructor({manufacturer, model, exposureTime, aperture, iso, takenAt, software}) {
        this.manufacturer = manufacturer;
        this.model = model;
        this.exposureTime = exposureTime;
        this.aperture = aperture;
        this.iso = iso;
        this.software = software;
        this.takenAt = takenAt;
        this.clone = this.clone.bind(this);
    }

    /**
     * @return {*}
     */
    get takenAt() {
        if (typeof this._takenAt === "undefined") {
            return;
        }

        const takenAt = moment.utc(this._takenAt);

        if (!takenAt.isValid()) {
            return;
        }

        return takenAt;
    }

    /**
     * @param {string} value
     */
    set takenAt(value) {
        this._takenAt = value;
    }

    /**
     * @return {Exif}
     */
    clone() {
        return new Exif({
            manufacturer: this.manufacturer,
            model: this.model,
            exposureTime: this.exposureTime,
            aperture: this.aperture,
            iso: this.iso,
            takenAt: this.takenAt,
            software: this.software,
        });
    }
}
