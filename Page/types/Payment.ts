import type {Null} from "./Null";

export type Payment = {
    Id?: Null<number>,
    ObjectId?: Null<number>,
    NationId?: Null<number>,
    StateId?: Null<number>,
    Method?: Null<0 | 1 | 2 | 3 | 4 | 5 | 6 >,
    Month?: Null<number>,
    Year?: Null<number>,
    Card?: Null<number>,
    Code?: Null<string>,
    City?: Null<string>,
    Last?: Null<string>,
    First?: Null<string>,
    Print?: Null<string>,
    Other?: Null<string>,
    Suite?: Null<string>,
    Number?: Null<string>,
    Postal?: Null<string>,
    Address?: Null<string>,
    Company?: Null<string>,
    Location?: Null<string>
}