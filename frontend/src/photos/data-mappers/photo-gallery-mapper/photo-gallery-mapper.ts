import {Photo} from '../../../shared/models';

export class PhotoGalleryMapper {
    id:number;
    description:string;
    url:string;
    exif:{
        manufacturer:string,
        model:string,
        exposure_time:string,
        aperture:string,
        iso:number,
        taken_at:string,
    };
    thumbnails:{
        medium:{
            width:number,
            height:number,
            url:string,
        },
        small:{
            width:number,
            height:number,
            url:string,
        },
    };

    constructor(photo:Photo) {
        this.id = photo.id;
        this.description = photo.description;
        this.url = photo.absolute_url;
        this.exif = photo.exif;
        this.thumbnails = {
            medium: {
                width: photo.thumbnails[0].width,
                height: photo.thumbnails[0].height,
                url: photo.thumbnails[0].absolute_url,
            },
            small: {
                width: photo.thumbnails[1].width,
                height: photo.thumbnails[1].height,
                url: photo.thumbnails[1].absolute_url,
            }
        };
    }
}