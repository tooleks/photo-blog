import {Injectable} from '@angular/core';
import {GalleryImage} from '../../lib';
import {ExifToStringMapper} from './exif-to-string.mapper';
import {AppService} from '../../shared';

@Injectable()
export class PhotoToGalleryImageMapper {
    constructor(protected exifMapper:ExifToStringMapper, protected app:AppService) {
    }

    map(object):GalleryImage {
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
            exif: this.exifMapper.map(object.exif),
        });
    }
}
