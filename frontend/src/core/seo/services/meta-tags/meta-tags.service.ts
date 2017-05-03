import {Injectable} from '@angular/core';
import {Meta, MetaDefinition} from '@angular/platform-browser';

@Injectable()
export class MetaTagsService {
    constructor(protected documentMeta:Meta) {
        this.initDefaults();
    }

    protected initDefaults():void {
        const tags:Array<MetaDefinition> = [
            // General meta tags.
            {name: 'description', content: ''},
            // Open Graph related meta tags.
            {property: 'og:title', content: ''},
            {property: 'og:type', content: 'article'},
            {property: 'og:url', content: ''},
            {property: 'og:image', content: ''},
            {property: 'og:description', content: ''},
            {property: 'og:site_name', content: ''},
            // Twitter related meta tags.
            {name: 'twitter:card', content: 'summary_large_image'},
            {name: 'twitter:title', content: ''},
            {name: 'twitter:image', content: ''},
        ];
        this.documentMeta.addTags(tags);
    }

    setWebsiteName(content:string):this {
        this.documentMeta.updateTag({property: 'og:site_name', content: content}, 'property="og:site_name"');
        return this;
    }

    setUrl(content:string):this {
        this.documentMeta.updateTag({property: 'og:url', content: content}, 'property="og:url"');
        return this;
    }

    setTitle(content:string):this {
        this.documentMeta.updateTag({property: 'og:title', content: content}, 'property="og:title"');
        this.documentMeta.updateTag({name: 'twitter:title', content: content}, 'name="twitter:title"');
        return this;
    }

    setDescription(content:string):this {
        this.documentMeta.updateTag({name: 'description', content: content}, 'name="description"');
        this.documentMeta.updateTag({property: 'og:description', content: content}, 'property="og:description"');
        return this;
    }

    setImage(content:string):this {
        this.documentMeta.updateTag({property: 'og:image', content: content}, 'property="og:image"');
        this.documentMeta.updateTag({name: 'twitter:image', content: content}, 'name="twitter:image"');
        return this;
    }
}
