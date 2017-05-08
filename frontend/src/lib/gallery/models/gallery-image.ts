import {GalleryImageAttributes} from './gallery-image-attributes';

export class GalleryImage {
    protected attributes:GalleryImageAttributes = new GalleryImageAttributes;

    constructor(attributes = {}) {
        this.setAttributes(attributes);
    }

    clone():GalleryImage {
        const cloned = new GalleryImage;
        cloned.setAttributes(this.getAttributes());
        return cloned;
    }

    protected callMethod(methodName:string, parameters) {
        if (typeof this[methodName] === 'function') {
            this[methodName](...parameters);
        }
    }

    setAttributes(attributes):this {
        for (let attributeName in attributes) {
            if (attributes.hasOwnProperty(attributeName)) {
                let setterName = 'set' + attributeName.charAt(0).toUpperCase() + attributeName.slice(1);
                this.callMethod(setterName, [attributes[attributeName]]);
            }
        }
        return this;
    }

    getAttributes():GalleryImageAttributes {
        return this.attributes;
    }

    setId(id:number):this {
        this.attributes.id = Number(id);
        return this;
    }

    getId():number {
        return this.attributes.id;
    }

    setViewUrl(viewUrl:string):this {
        this.attributes.viewUrl = String(viewUrl);
        return this;
    }

    getViewUrl():string {
        return this.attributes.viewUrl;
    }

    setSmallSizeUrl(smallSizeUrl:string):this {
        this.attributes.smallSizeUrl = String(smallSizeUrl);
        return this;
    }

    getSmallSizeUrl():string {
        return this.attributes.smallSizeUrl;
    }

    setSmallSizeHeight(smallSizeHeight:number):this {
        this.attributes.smallSizeHeight = Number(smallSizeHeight);
        return this;
    }

    getSmallSizeHeight():number {
        return this.attributes.smallSizeHeight;
    }

    setSmallSizeWidth(smallSizeWidth:number):this {
        this.attributes.smallSizeWidth = Number(smallSizeWidth);
        return this;
    }

    getSmallSizeWidth():number {
        return this.attributes.smallSizeWidth;
    }

    setFullSizeUrl(fullSizeUrl:string):this {
        this.attributes.fullSizeUrl = String(fullSizeUrl);
        return this;
    }

    getFullSizeUrl():string {
        return this.attributes.fullSizeUrl;
    }

    setLargeSizeUrl(largeSizeUrl:string):this {
        this.attributes.largeSizeUrl = String(largeSizeUrl);
        return this;
    }

    getLargeSizeUrl():string {
        return this.attributes.largeSizeUrl;
    }

    setLargeSizeHeight(largeSizeHeight:number):this {
        this.attributes.largeSizeHeight = Number(largeSizeHeight);
        return this;
    }

    getLargeSizeHeight():number {
        return this.attributes.largeSizeHeight;
    }

    setLargeSizeWidth(largeSizeWidth:number):this {
        this.attributes.largeSizeWidth = Number(largeSizeWidth);
        return this;
    }

    getLargeSizeWidth():number {
        return this.attributes.largeSizeWidth;
    }

    setAvgColor(avgColor:string):this {
        this.attributes.avgColor = String(avgColor);
        return this;
    }

    getAvgColor():string {
        return this.attributes.avgColor;
    }

    setDescription(description:string):this {
        this.attributes.description = String(description);
        return this;
    }

    getDescription():string {
        return this.attributes.description;
    }

    setExif(exif:string):this {
        this.attributes.exif = String(exif);
        return this;
    }

    getExif():string {
        return this.attributes.exif;
    }
}
