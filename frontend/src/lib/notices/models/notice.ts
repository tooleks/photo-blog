export class Notice {
    createdAt: number;
    type: string;
    title: string;
    text: string;

    constructor(createdAt: number, type: string, title: string, text: string = '') {
        this.createdAt = createdAt;
        this.type = type;
        this.title = title;
        this.text = text;
    }

    isEqual(notice: Notice) {
        return this.type === notice.type &&
            this.title === notice.title &&
            this.text == notice.text;
    }
}
