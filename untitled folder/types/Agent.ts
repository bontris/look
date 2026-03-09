import type {Null} from "./Null";

export type Agent = {
    Id?: Null<number>;
    Ud?: Null<string>;
    UserId?: Null<number>;
    DomainId: Null<number>;
    Lock?: Null<boolean>;
    Test?: Null<boolean>;
    Hide?: Null<boolean>;
    Tint?: Null<string>;
    Code?: Null<string>;
    Mail?: string;
    Last?: string;
    First?: string;
    Image?: Null<string>;
    Mobile?: string;
    Status?: number;
    Comment?: Null<string>;
    Creation?: Null<Date>;
    Deletion?: Null<Date>;
    Modification?: Null<Date>;
}