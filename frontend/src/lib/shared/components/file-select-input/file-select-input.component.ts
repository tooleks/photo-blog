import {Component, Input, Output, ElementRef, EventEmitter} from '@angular/core';

@Component({
    selector: 'file-select-input',
    templateUrl: 'file-select-input.component.html',
})
export class FileSelectInputComponent {
    @Input() disabled:boolean;
    @Output() onSelect:EventEmitter<File> = new EventEmitter<File>();

    constructor(protected elementRef:ElementRef) {
    }

    protected onChange = ():void => {
        if (this.isFile()) {
            this.onSelect.emit(this.getFile());
        }
    };

    protected getFile = ():File => {
        return this.elementRef.nativeElement.firstElementChild.files[0];
    };

    protected isFile = ():boolean => {
        return this.elementRef.nativeElement.firstElementChild.files.length > 0;
    };
}
