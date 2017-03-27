export class GalleryItem {
    private id:number;
    private full_size_url:string;
    private small_size_url:string;
    private small_size_height:number;
    private small_size_width:number;
    private large_size_url:string;
    private large_size_height:number;
    private large_size_width:number;
    private avg_color:string;
    private description:string;
    private exif:string;

    setId(id:number):this {
        this.id = id;
        return this;
    }

    getId():number {
        return this.id;
    }

    setSmallSizeUrl(small_size_url:string):this {
        this.small_size_url = small_size_url;
        return this;
    }

    getSmallSizeUrl():string {
        return this.small_size_url;
    }

    setSmallSizeHeight(small_size_height:number):this {
        this.small_size_height = small_size_height;
        return this;
    }

    getSmallSizeHeight():number {
        return this.small_size_height;
    }

    setSmallSizeWidth(small_size_width:number):this {
        this.small_size_width = small_size_width;
        return this;
    }

    getSmallSizeWidth():number {
        return this.small_size_width;
    }

    setFullSizeUrl(full_size_url:string):this {
        this.full_size_url = full_size_url;
        return this;
    }

    getFullSizeUrl():string {
        return this.full_size_url;
    }

    setLargeSizeUrl(large_size_url:string):this {
        this.large_size_url = large_size_url;
        return this;
    }

    getLargeSizeUrl():string {
        return this.large_size_url;
    }

    setLargeSizeHeight(large_size_height:number):this {
        this.large_size_height = large_size_height;
        return this;
    }

    getLargeSizeHeight():number {
        return this.large_size_height;
    }

    setLargeSizeWidth(large_size_width:number):this {
        this.large_size_width = large_size_width;
        return this;
    }

    getLargeSizeWidth():number {
        return this.large_size_width;
    }

    setAvgColor(avg_color:string):this {
        this.avg_color = avg_color;
        return this;
    }

    getAvgColor():string {
        return this.avg_color;
    }

    setDescription(description:string):this {
        this.description = description;
        return this;
    }

    getDescription():string {
        return this.description;
    }

    setExif(exif:string):this {
        this.exif = exif;
        return this;
    }

    getExif():string {
        return this.exif;
    }
}
