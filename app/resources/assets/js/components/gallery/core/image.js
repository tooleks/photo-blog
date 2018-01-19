export default class Image {
    constructor(model) {
        const width = model.thumbnail.width || 0;
        const height = model.thumbnail.height || 0;
        this.setModel(model);
        this.setWidth(width);
        this.setHeight(height);
    }

    setModel(model) {
        this.model = model;
    }

    getModel() {
        return this.model;
    }

    setWidth(width) {
        this.width = parseInt(width);
    }

    getWidth() {
        return this.width;
    }

    setHeight(height) {
        this.height = parseInt(height);
    }

    getHeight() {
        return this.height;
    }

    clone() {
        const image = new Image(this.getModel());
        image.setWidth(this.getWidth());
        image.setHeight(this.getHeight());
        return image;
    }

    scale(rate) {
        const width = this.getWidth() * 100 / rate;
        const height = this.getHeight() * 100 / rate;
        const image = this.clone();
        image.setWidth(width);
        image.setHeight(height);
        return image;
    }

    scaleToHeight(height) {
        const rate = this.getHeight() * 100 / height;
        return this.scale(rate);
    }

    scaleToWidth(width) {
        const rate = this.getWidth() * 100 / width;
        return this.scale(rate);
    }
}
