export function scale(value:number, scaleRate:number):number {
    return value * 100 / scaleRate;
}

export function sum(values:Array<number>):number {
    let sum = 0;
    values.forEach((value:number) => sum += value);
    return sum;
}
