import type {Null} from "./Null";

export type Hour = {
    Id?: Null<number>,
    OrderId?: Null<number>,
    Day?: Null<0 | 1 | 2 | 3 | 4 | 5 | 6 >,
    Full?: Null<boolean | number>,
    Only?: Null<boolean | number>,
    Open?: Null<string>,
    Close?: Null<string>
}