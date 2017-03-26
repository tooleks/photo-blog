import {Exif} from './exif';
import {Tag} from './tag';
import {Thumbnail} from './thumbnail';

export class PublishedPhoto {
    id:number;
    created_by_user_id:number;
    absolute_url:string;
    avg_color:string;
    description:string;
    created_at:string;
    updated_at:string;
    exif:Exif = new Exif;
    tags:Tag[] = [];
    thumbnails:{large:Thumbnail, medium:Thumbnail} = {large: new Thumbnail, medium: new Thumbnail};
}
