import {Injectable} from '@angular/core';
import {AppService} from '../../shared';

@Injectable()
export class PhotoToLinkedDataMapper {
    constructor(protected app:AppService) {
    }

    map(object:any):any {
        return {
            '@context': 'http://schema.org',
            '@type': 'Article',
            'mainEntityOfPage': {
                '@type': 'WebPage',
                '@id': `${this.app.getUrl()}/photos?show=${object.id}`,
            },
            'headline': object.description,
            'image': {
                '@type': 'ImageObject',
                'url': object.thumbnails.large.url,
                'height': object.thumbnails.large.height,
                'width': object.thumbnails.large.width,
            },
            'datePublished': object.created_at,
            'dateModified': object.updated_at,
            'author': {
                '@type': 'Person',
                'name': this.app.getAuthor(),
            },
            'publisher': {
                '@type': 'Organization',
                'name': this.app.getName(),
                'logo': {
                    '@type': 'ImageObject',
                    'url': this.app.getImage(),
                },
            },
            'description': object.description,
        };
    }
}
