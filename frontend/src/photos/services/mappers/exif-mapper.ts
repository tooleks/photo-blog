import {Exif} from '../../../shared/models';

export class ExifMapper {

    static map(item:any):Exif {
        let exif = new Exif;
        exif.manufacturer = item.manufacturer;
        exif.model = item.model;
        exif.exposure_time = item.exposure_time;
        exif.aperture = item.aperture;
        exif.iso = item.iso;
        exif.taken_at = item.taken_at;
        return exif;
    }

}
