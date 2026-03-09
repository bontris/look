import type {Null} from "./Null";

export type Resource = {
    Id?: Null<number>;
    Ud?: Null<string>;
    Type?: Null<1 | 2 | 3>;
    Code?: Null<string>;
    Name?: Null<string>;
    Price?: Null<number>;
    Description?: Null<string>;
    Creation?: Null<Date>;
    Deletion?: Null<Date>;
    Modification?: Null<Date>;
}