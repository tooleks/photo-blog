import {Tag} from '../../../shared/models';

export class TagMapper {

    static map(item:any):Tag {
        let tag = new Tag;
        tag.text = item.text;
        return tag;
    }

}
