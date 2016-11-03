import {Component, Input, Output, EventEmitter} from '@angular/core';
import {Tag} from './tag';

@Component({
    selector: 'tags-select-input',
    template: require('./tags-select-input.component.html'),
})
export class TagsSelectInputComponent {
    @Input() tags:Tag[];
    @Output() tagsChange:EventEmitter<Tag[]> = new EventEmitter<Tag[]>();
    items:string[] = [];

    ngOnChanges(changes:any) {
        if (!this.items.length && changes['tags']) {
            this.tags.forEach((tag:Tag) => {
                this.items.push(tag.text);
            });
        }
    }

    onAdd(item:string) {
        this.tags.push({text: item});
        this.tagsChange.emit(this.tags);
    }

    onRemove(item:string) {
        let tags:Tag[] = [];
        this.tags.forEach((tag:Tag) => {
            if (tag.text != item) {
                tags.push(tag);
            }
        });
        this.tags = tags;
        this.tagsChange.emit(this.tags);
    }

    transform(item:string) {
        return item.toLowerCase();
    }
}
