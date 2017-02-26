import {Exif} from './exif';
import {Tag} from './tag';
import {Thumbnail} from './thumbnail';

export class Photo {
    id:number;
    uploaded_photo_id:number;
    user_id:number;
    absolute_url:string;
    avg_color:string;
    description:string;
    created_at:string;
    updated_at:string;
    exif:Exif = new Exif;
    tags:Tag[] = [];
    thumbnails:Thumbnail[] = [];
}
