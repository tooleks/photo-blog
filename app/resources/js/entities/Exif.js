import moment from "moment";

export default class Exif {
    /**
     * @param {Object} attributes
     * @param {string} attributes.manufacturer
     * @param {string} attributes.model
     * @param {string} attributes.exposureTime
     * @param {string} attributes.aperture
     * @param {string} attributes.focalLength
     * @param {string} attributes.focalLengthIn35mm
     * @param {string} attributes.iso
     * @param {string} attributes.takenAt
     * @param {string} attributes.software
     */
    constructor({manufacturer, model, exposureTime, aperture, focalLength, focalLengthIn35mm, iso, takenAt, software}) {
        this.manufacturer = manufacturer;
        this.model = model;
        this.exposureTime = exposureTime;
        this.aperture = aperture;
        this.focalLength = focalLength;
        this.focalLengthIn35mm = focalLengthIn35mm;
        this.iso = iso;
        this.software = software;
        this.takenAt = takenAt;
        this.clone = this.clone.bind(this);
    }

    /**
     * @returns {*}
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
     * @returns {Exif}
     */
    clone() {
        return new Exif({
            manufacturer: this.manufacturer,
            model: this.model,
            exposureTime: this.exposureTime,
            aperture: this.aperture,
            focalLength: this.focalLength,
            focalLengthIn35mm: this.focalLengthIn35mm,
            iso: this.iso,
            takenAt: this.takenAt,
            software: this.software,
        });
    }
}
