import {Component, Input, Inject, ElementRef} from '@angular/core';

@Component({
    selector: 'file-select-input',
    template: require('./file-select-input.component.html'),
})
export class FileSelectInputComponent {
    @Input() disabled:boolean;
    @Input() onChangeCallback:any;

    constructor(@Inject(ElementRef) private element:ElementRef) {
    }

    private processCallback = (callback:any, args?:any[]) => {
        return typeof callback === 'function' ? Promise.resolve(callback(...args)) : Promise.reject(new Error);
    };

    onChange() {
        if (this.isFile()) {
            this.processCallback(this.onChangeCallback, [this.getFile()]);
        }
    }

    getFile = ():FileList => {
        return this.element.nativeElement.firstElementChild.files[0];
    };

    isFile = ():boolean => {
        return this.element.nativeElement.firstElementChild.files.length > 0;
    };
}
