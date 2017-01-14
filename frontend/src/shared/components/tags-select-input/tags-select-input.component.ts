import {Component, Input, Output, EventEmitter, SimpleChanges} from '@angular/core';

@Component({
    selector: 'tags-select-input',
    template: require('./tags-select-input.component.html'),
    styles: [require('./tags-select-input.component.css').toString()]
})
export class TagsSelectInputComponent {
    @Input() tags:Array<any> = [];
    @Output() tagsChange:EventEmitter<Array<any>> = new EventEmitter<Array<any>>();

    items:Array<any> = [];

    ngOnChanges(changes:SimpleChanges) {
        if (changes['tags'] && !this.items.length) {
            this.items = this.tags.map((tag:any) => tag.text);
        }
    }

    onAdd = (text:string) => {
        this.tags.push({text: text});
        this.tagsChange.emit(this.tags);
    };

    onRemove = (text:string) => {
        this.tags = this.tags.filter((tag:any) => tag.text !== text);
        this.tagsChange.emit(this.tags);
    };

    transform = (text:string) => {
        return text.toLowerCase();
    };
}
