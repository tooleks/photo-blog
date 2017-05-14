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

    setSource(source):this {
        this.attributes.source = source;
        return this;
    }

    getSource() {
        return this.attributes.source;
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

    setGridSizeUrl(gridSizeUrl:string):this {
        this.attributes.gridSizeUrl = String(gridSizeUrl);
        return this;
    }

    getGridSizeUrl():string {
        return this.attributes.gridSizeUrl;
    }

    setGridSizeHeight(gridSizeHeight:number):this {
        this.attributes.gridSizeHeight = Number(gridSizeHeight);
        return this;
    }

    getGridSizeHeight():number {
        return this.attributes.gridSizeHeight;
    }

    setGridSizeWidth(gridSizeWidth:number):this {
        this.attributes.gridSizeWidth = Number(gridSizeWidth);
        return this;
    }

    getGridSizeWidth():number {
        return this.attributes.gridSizeWidth;
    }

    setFullSizeUrl(fullSizeUrl:string):this {
        this.attributes.fullSizeUrl = String(fullSizeUrl);
        return this;
    }

    getFullSizeUrl():string {
        return this.attributes.fullSizeUrl;
    }

    setViewerSizeUrl(viewerSizeUrl:string):this {
        this.attributes.viewerSizeUrl = String(viewerSizeUrl);
        return this;
    }

    getViewerSizeUrl():string {
        return this.attributes.viewerSizeUrl;
    }

    setViewerSizeHeight(viewerSizeHeight:number):this {
        this.attributes.viewerSizeHeight = Number(viewerSizeHeight);
        return this;
    }

    getViewerSizeHeight():number {
        return this.attributes.viewerSizeHeight;
    }

    setViewerSizeWidth(viewerSizeWidth:number):this {
        this.attributes.viewerSizeWidth = Number(viewerSizeWidth);
        return this;
    }

    getViewerSizeWidth():number {
        return this.attributes.viewerSizeWidth;
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
