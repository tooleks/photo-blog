import {Component, OnChanges, Input, Output, EventEmitter, SimpleChanges} from '@angular/core';

@Component({
    selector: 'tags-select-input',
    templateUrl: 'tags-select-input.component.html',
    styleUrls: ['tags-select-input.component.css']
})
export class TagsSelectInputComponent implements OnChanges {
    @Input() tags:Array<any> = [];
    @Output() tagsChange:EventEmitter<Array<any>> = new EventEmitter<Array<any>>();

    items:Array<any> = [];

    ngOnChanges(changes:SimpleChanges) {
        if (changes['tags'] && !this.items.length) {
            this.items = this.tags.map((tag:any) => tag.text);
        }
    }

    onAdd = (text:string):void => {
        this.tags.push({text: text});
        this.tagsChange.emit(this.tags);
    };

    onRemove = (text:string):void => {
        this.tags = this.tags.filter((tag:any) => tag.text !== text);
        this.tagsChange.emit(this.tags);
    };

    transform = (text:string):string => {
        return text.toLowerCase();
    };
}
