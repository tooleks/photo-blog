import {GalleryImage} from '../../shared/components/gallery';

export class PhotoGalleryImageMapper {
    static map(item:any):any {
        return (item instanceof Array)
            ? PhotoGalleryImageMapper.mapMultiple(item)
            : PhotoGalleryImageMapper.mapSingle(item);
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
            exif: item.exif,
        });
    }

    private static mapMultiple(items:Array<any>):Array<GalleryImage> {
        return items.map(PhotoGalleryImageMapper.mapSingle);
    }
}
