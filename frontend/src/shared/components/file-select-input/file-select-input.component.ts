import {Component, Input, Output, Inject, ElementRef, EventEmitter} from '@angular/core';

@Component({
    selector: 'file-select-input',
    template: require('./file-select-input.component.html'),
})
export class FileSelectInputComponent {
    @Input() disabled:boolean;
    @Output() afterSelect:EventEmitter<File> = new EventEmitter<File>();

    constructor(@Inject(ElementRef) private elementRef:ElementRef) {
    }

    private onChange = () => {
        if (this.isFile()) {
            this.afterSelect.emit(this.getFile());
        }
    };

    private getFile = ():File => {
        return this.elementRef.nativeElement.firstElementChild.files[0];
    };

    private isFile = ():boolean => {
        return this.elementRef.nativeElement.firstElementChild.files.length > 0;
    };
}
