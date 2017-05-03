import {GalleryImage} from '../../lib';
import {ExifToStringMapper} from './exif-to-string-mapper';

export class PhotoToGalleryImageMapper {
    static map(object:any):any {
        return (object instanceof Array)
            ? PhotoToGalleryImageMapper.mapMultiple(object)
            : PhotoToGalleryImageMapper.mapSingle(object);
    }

    protected static mapSingle(object:any):GalleryImage {
        return new GalleryImage({
            id: object.id,
            viewUrl: `/photos?show=${object.id}`,
            fullSizeUrl: object.url,
            smallSizeUrl: object.thumbnails.medium.url,
            smallSizeHeight: object.thumbnails.medium.height,
            smallSizeWidth: object.thumbnails.medium.width,
            largeSizeUrl: object.thumbnails.large.url,
            largeSizeHeight: object.thumbnails.large.height,
            largeSizeWidth: object.thumbnails.large.width,
            avgColor: object.avg_color,
            description: object.description,
            exif: ExifToStringMapper.map(object.exif),
        });
    }

    protected static mapMultiple(objects:Array<any>):Array<GalleryImage> {
        return objects.map(PhotoToGalleryImageMapper.mapSingle);
    }
}
