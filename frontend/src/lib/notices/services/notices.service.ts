import {Injectable} from '@angular/core';
import {Notice} from '../models';

@Injectable()
export class NoticesService {
    protected notices: Array<Notice> = [];
    public deleteTimeout: number = 5000;
    public deleteInterval: number = 42;

    constructor() {
        // #browser-specific
        if (typeof (window) !== 'undefined') {
            setInterval(() => this.deleteOutdated(), this.deleteInterval);
        }
    }

    exists(newNotice: Notice) {
        return this.notices.filter((notice: Notice) => notice.isEqual(newNotice)).length;
    }

    success(title: string, text?: string): void {
        const newNotice = new Notice(Math.floor(Date.now()), 'success', title, text);
        if (!this.exists(newNotice)) {
            this.notices.push(newNotice);
        }
    }

    warning(title: string, text?: string): void {
        const newNotice = new Notice(Math.floor(Date.now()), 'warning', title, text);
        if (!this.exists(newNotice)) {
            this.notices.push(newNotice);
        }
    }

    error(title: string, text?: string): void {
        const newNotice = new Notice(Math.floor(Date.now()), 'error', title, text);
        if (!this.exists(newNotice)) {
            this.notices.push(newNotice);
        }
    }

    info(title: string, text?: string): void {
        const newNotice = new Notice(Math.floor(Date.now()), 'info', title, text);
        if (!this.exists(newNotice)) {
            this.notices.push(newNotice);
        }
    }

    get(): Array<Notice> {
        return this.notices;
    }

    deleteAll(): void {
        this.notices = [];
    }

    deleteByIndex(index: number): void {
        this.notices = this.notices.filter((element, elementIndex) => elementIndex !== index);
    }

    deleteOutdated(): void {
        this.notices = this.notices.filter((notice: Notice) => notice.createdAt + this.deleteTimeout > Math.floor(Date.now()));
    }
}
