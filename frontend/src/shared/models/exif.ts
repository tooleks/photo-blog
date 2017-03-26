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
}
