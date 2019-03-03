export default class Image {
    /**
     * @param {*} model
     */
    constructor(model) {
        const width = model.thumbnail.width || 0;
        const height = model.thumbnail.height || 0;
        this.model = model;
        this.width = width;
        this.height = height;
    }

    get model() {
        return this._model;
    }

    set model(value) {
        this._model = value;
    }

    get width() {
        return this._width;
    }

    set width(value) {
        this._width = parseInt(value);
    }

    get height() {
        return this._height;
    }

    set height(value) {
        this._height = parseInt(value);
    }

    /**
     * @returns {Image}
     */
    clone() {
        const image = new Image(this.model);
        image.width = this.width;
        image.height = this.height;
        return image;
    }

    /**
     * @param {number} ratio
     * @returns {Image}
     */
    scale(ratio) {
        const width = this.width * 100 / ratio;
        const height = this.height * 100 / ratio;
        const image = this.clone();
        image.width = width;
        image.height = height;
        return image;
    }

    /**
     * @param {number} height
     * @returns {Image}
     */
    scaleToHeight(height) {
        const ratio = this.height * 100 / height;
        return this.scale(ratio);
    }

    /**
     * @param {number} width
     * @returns {Image}
     */
    scaleToWidth(width) {
        const ratio = this.width * 100 / width;
        return this.scale(ratio);
    }
}
