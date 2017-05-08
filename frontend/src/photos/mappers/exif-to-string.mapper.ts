import {Injectable} from '@angular/core';

@Injectable()
export class ExifToStringMapper {
    map(object:any):string {
        const exif:Array<string> = [];
        if (String(object.manufacturer).trim()) {
            exif.push('Manufacturer: ' + object.manufacturer);
        }
        if (String(object.model).trim()) {
            exif.push('Model: ' + object.model);
        }
        if (String(object.exposure_time).trim()) {
            exif.push('Exposure Time: ' + object.exposure_time);
        }
        if (String(object.aperture).trim()) {
            exif.push('Aperture: ' + object.aperture);
        }
        if (String(object.iso).trim()) {
            exif.push('Iso: ' + object.iso);
        }
        if (String(object.taken_at).trim()) {
            exif.push('Taken At: ' + object.taken_at);
        }
        return exif.join(', ');
    }
}
