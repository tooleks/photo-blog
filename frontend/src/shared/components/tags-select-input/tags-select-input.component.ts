import {Component, Input, Output, EventEmitter, SimpleChanges} from '@angular/core';

@Component({
    selector: 'tags-select-input',
    template: require('./tags-select-input.component.html'),
    styles: [require('./tags-select-input.component.css').toString()]
})
export class TagsSelectInputComponent {
    @Input() tags:Array<any>;
    @Output() tagsChange:EventEmitter<Array<any>> = new EventEmitter<Array<any>>();
    items:Array<string> = [];

    ngOnChanges(changes:SimpleChanges) {
        if (!this.items.length && changes['tags']) {
            this.tags.forEach((tag:any) => {
                this.items.push(tag.text);
            });
        }
    }

    onAdd = (item:string) => {
        this.tags.push({text: item});
        this.tagsChange.emit(this.tags);
    };

    onRemove = (item:string) => {
        let tags:Array<any> = [];
        this.tags.forEach((tag:any) => {
            if (tag.text != item) {
                tags.push(tag);
            }
        });
        this.tags = tags;
        this.tagsChange.emit(this.tags);
    };

    transform = (item:string) => {
        return item.toLowerCase();
    };
}
