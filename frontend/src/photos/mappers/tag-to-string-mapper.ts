export class TagToStringMapper {
    static map(item:any):any {
        return (item instanceof Array)
            ? TagToStringMapper.mapMultiple(item)
            : TagToStringMapper.mapSingle(item);
    }

    private static mapSingle(item:any):string {
        return item.text;
    }

    private static mapMultiple(items:Array<any>):Array<string> {
        return items.map(TagToStringMapper.mapSingle);
    }
}
