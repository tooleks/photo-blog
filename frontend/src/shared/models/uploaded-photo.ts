import {Thumbnail} from './thumbnail';

export class UploadedPhoto {
    id:number;
    user_id:number;
    absolute_url:string;
    created_at:string;
    updated_at:string;
    thumbnails:Thumbnail[] = [];
}
