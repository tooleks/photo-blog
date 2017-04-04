export class StringToTagMapper {
    static map(item:any):any {
        return (item instanceof Array)
            ? StringToTagMapper.mapMultiple(item)
            : StringToTagMapper.mapSingle(item);
    }

    private static mapSingle(item:string):{text:string} {
        return {text: item};
    }

    private static mapMultiple(items:Array<string>):Array<{text:string}> {
        return items.map(StringToTagMapper.mapSingle);
    }
}
