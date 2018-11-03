import Image from "./Image";

const DEFAULT_ROW_MAX_WIDTH = 300;
const DEFAULT_ROW_MAX_HEIGHT = 300;

export default class Masonry {
    constructor(options) {
        this.setOptions(options);
        this.setRows([]);
        this.setCurrentRow([]);
    }

    setOptions(options) {
        const defaultOptions = {
            rowMaxWidth: DEFAULT_ROW_MAX_WIDTH,
            rowMaxHeight: DEFAULT_ROW_MAX_HEIGHT,
        };
        this.options = {...defaultOptions, ...options};
    }

    getOptions() {
        return this.options;
    }

    setRows(rows) {
        this.rows = rows;
    }

    getRows() {
        return this.rows;
    }

    setCurrentRow(currentRow) {
        this.currentRow = currentRow;
    }

    getCurrentRow() {
        return this.currentRow;
    }

    getCurrentRowTotalWidth() {
        return this.getCurrentRow().reduce((totalWidth, image) => totalWidth + image.width, 0);
    }

    getRowsImages() {
        return [].concat.apply([], this.getRows());
    }

    reset() {
        this.setCurrentRow([]);
        this.setRows([]);
    }

    process(images) {
        const uniqueImages = images.map((image) => new Image(image)).filter((image) => !this.exists(image));
        const lastRowImages = this.getRows().pop() || [];
        const newImages = lastRowImages.concat(uniqueImages);
        newImages.forEach((newImage, index) => {
            const scaledNewImage = newImage.scaleToHeight(this.getOptions().rowMaxHeight);
            this.getCurrentRow().push(scaledNewImage);
            this.renderCurrentRowIfFilled(index === newImages.length - 1);
        });
    }

    exists(newImage) {
        return this.getRowsImages().some((image) => image.model.equals(newImage.model));
    }

    renderCurrentRowIfFilled(force) {
        let row = [];

        if (this.getCurrentRowTotalWidth() > this.getOptions().rowMaxWidth) {
            const ratio = this.getCurrentRowTotalWidth() * 100 / this.getOptions().rowMaxWidth;
            row = this.getCurrentRow().map((image) => image.scale(ratio));
        }

        if (force && !row.length) {
            row = this.getCurrentRow();
        }

        if (row.length) {
            this.setCurrentRow([]);
            this.getRows().push(row);
        }
    }
}
