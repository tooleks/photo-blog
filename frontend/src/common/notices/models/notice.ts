export class Notice {
    timestampCreated:number;
    type:string;
    title:string;
    text:string;

    constructor(timestampCreated:number, type:string, title:string, text:string = '') {
        this.timestampCreated = timestampCreated;
        this.type = type;
        this.title = title;
        this.text = text;
    }
}
