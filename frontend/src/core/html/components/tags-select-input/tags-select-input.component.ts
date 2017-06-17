import {Component, Input, Output, EventEmitter, SimpleChanges, OnChanges} from '@angular/core';
import 'rxjs/add/operator/filter';

@Component({
    selector: 'tags-select-input',
    templateUrl: 'tags-select-input.component.html',
})
export class TagsSelectInputComponent implements OnChanges {
    inputValue: string = '';
    @Input() inputClass: string = '';
    @Input() tags: Array<any> = [];
    @Output() tagsChange: EventEmitter<Array<any>> = new EventEmitter<Array<any>>();

    ngOnChanges(changes: SimpleChanges): void {
        if (changes['tags']) {
            this.inputValue = this.tags.map(tag => tag.value).join(',');
        }
    }

    getInputValue(): string {
        return this.inputValue;
    }

    onChangeInputValue(newInputValue: string): void {
        this.inputValue = newInputValue.trim().split(' ').join('_').toLowerCase();
        this.tags = this.inputValue.split(',').map(value => {
            return {value: value};
        });
        this.tagsChange.emit(this.tags);
    }
}
