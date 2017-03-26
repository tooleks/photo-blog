import {Thumbnail} from '../../../shared/models';

export class ThumbnailMapper {

    static map(item:any):Thumbnail {
        let thumbnail = new Thumbnail;
        thumbnail.id = item.id;
        thumbnail.absolute_url = item.absolute_url;
        thumbnail.width = item.width;
        thumbnail.height = item.height;
        return thumbnail;
    }

}
