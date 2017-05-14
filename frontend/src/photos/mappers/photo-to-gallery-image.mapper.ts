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
            gridSizeUrl: object.thumbnails.medium.url,
            gridSizeHeight: object.thumbnails.medium.height,
            gridSizeWidth: object.thumbnails.medium.width,
            viewerSizeUrl: object.thumbnails.large.url,
            viewerSizeHeight: object.thumbnails.large.height,
            viewerSizeWidth: object.thumbnails.large.width,
            avgColor: object.avg_color,
            description: object.description,
            exif: this.exifMapper.map(object.exif),
        });
    }
}
