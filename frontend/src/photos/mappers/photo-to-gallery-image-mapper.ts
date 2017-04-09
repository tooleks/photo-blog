import {GalleryImage} from '../../lib/gallery';
import {ExifToStringMapper} from './exif-to-string-mapper';

export class PhotoToGalleryImageMapper {
    static map(item:any):any {
        return (item instanceof Array)
            ? PhotoToGalleryImageMapper.mapMultiple(item)
            : PhotoToGalleryImageMapper.mapSingle(item);
    }

    private static mapSingle(item:any):GalleryImage {
        return new GalleryImage({
            id: item.id,
            fullSizeUrl: item.url,
            smallSizeUrl: item.thumbnails.medium.url,
            smallSizeHeight: item.thumbnails.medium.height,
            smallSizeWidth: item.thumbnails.medium.width,
            largeSizeUrl: item.thumbnails.large.url,
            largeSizeHeight: item.thumbnails.large.height,
            largeSizeWidth: item.thumbnails.large.width,
            avgColor: item.avg_color,
            description: item.description,
            exif: ExifToStringMapper.map(item.exif),
        });
    }

    private static mapMultiple(items:Array<any>):Array<GalleryImage> {
        return items.map(PhotoToGalleryImageMapper.mapSingle);
    }
}
