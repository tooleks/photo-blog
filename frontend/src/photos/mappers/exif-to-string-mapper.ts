export class ExifToStringMapper {
    static map(object:any):any {
        return (object instanceof Array)
            ? ExifToStringMapper.mapMultiple(object)
            : ExifToStringMapper.mapSingle(object);
    }

    protected static mapSingle(object:any):string {
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

    protected static mapMultiple(objects:Array<any>):Array<string> {
        return objects.map(ExifToStringMapper.mapSingle);
    }
}
