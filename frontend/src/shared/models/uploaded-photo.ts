import {Exif} from './exif';
import {Thumbnail} from './thumbnail';

export class UploadedPhoto {
    id:number;
    user_id:number;
    absolute_url:string;
    created_at:string;
    updated_at:string;
    exif:Exif = new Exif;
    thumbnails:Thumbnail[] = [];
}
