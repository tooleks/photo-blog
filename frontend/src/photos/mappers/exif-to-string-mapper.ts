export class ExifToStringMapper {
    static map(item:any):any {
        return (item instanceof Array)
            ? ExifToStringMapper.mapMultiple(item)
            : ExifToStringMapper.mapSingle(item);
    }

    protected static mapSingle(item:any):string {
        const exif:Array<string> = [];
        if (String(item.manufacturer).trim()) {
            exif.push('Manufacturer: ' + item.manufacturer);
        }
        if (String(item.model).trim()) {
            exif.push('Model: ' + item.model);
        }
        if (String(item.exposure_time).trim()) {
            exif.push('Exposure Time: ' + item.exposure_time);
        }
        if (String(item.aperture).trim()) {
            exif.push('Aperture: ' + item.aperture);
        }
        if (String(item.iso).trim()) {
            exif.push('Iso: ' + item.iso);
        }
        if (String(item.taken_at).trim()) {
            exif.push('Taken At: ' + item.taken_at);
        }
        return exif.join(', ');
    }

    protected static mapMultiple(items:Array<any>):Array<string> {
        return items.map(ExifToStringMapper.mapSingle);
    }
}
