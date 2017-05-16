import {Component, Input, Output, EventEmitter} from '@angular/core';
import 'rxjs/add/operator/filter';

@Component({
    selector: 'tags-select-input',
    templateUrl: 'tags-select-input.component.html',
})
export class TagsSelectInputComponent {
    @Input() tags: Array<any> = [];
    @Output() tagsChange: EventEmitter<Array<any>> = new EventEmitter<Array<any>>();

    onAdd(addedTag): void {
        this.tags.push(addedTag);
        this.tagsChange.emit(this.tags);
    }

    onRemove(removedTag): void {
        this.tags = this.tags.filter((tag) => tag.value !== removedTag.value);
        this.tagsChange.emit(this.tags);
    }

    transform(value: string): string {
        return value.split(' ').join('_').toLowerCase();
    }
}
