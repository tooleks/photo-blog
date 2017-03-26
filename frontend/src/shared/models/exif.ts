export class Exif {
    manufacturer:string;
    model:string;
    exposure_time:string;
    aperture:string;
    iso:string;
    taken_at:string;

    getManufacturer = ():string => {
        return String(this.manufacturer).trim();
    };

    getModel = ():string => {
        return String(this.model).trim();
    };

    getExposureTime = ():string => {
        return String(this.exposure_time).trim();
    };

    getAperture = ():string => {
        return String(this.aperture).trim();
    };

    getIso = ():string => {
        return String(this.iso).trim();
    };

    getTakenAt = ():string => {
        return String(this.taken_at).trim();
    };

    toString = ():string => {
        let exif:Array<string> = [];
        if (this.getManufacturer()) {
            exif.push('Manufacturer: ' + this.getManufacturer());
        }
        if (this.getModel()) {
            exif.push('Model: ' + this.getModel());
        }
        if (this.getExposureTime()) {
            exif.push('Exposure Time: ' + this.getExposureTime());
        }
        if (this.getAperture()) {
            exif.push('Aperture: ' + this.getAperture());
        }
        if (this.getIso()) {
            exif.push('Iso: ' + this.getIso());
        }
        return exif.join(', ');
    }
}
