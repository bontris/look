import type {Null} from "./Null";

export type User = {
    Id?: Null<number>;
    Ud?: Null<string>;
    RoleId?: Null<number>;
    TimeId?: Null<number>;
    DomainId: Null<number>;
    NationId?: Null<number>;
    Lock?: Null<boolean>;
    Test?: Null<boolean>;
    Hide?: Null<boolean>;
    Core?: Null<boolean>;
    Type: Null<number>;
    Tint?: Null<string>;
    Code?: Null<string>;
    Nick?: Null<string>;
    Pass?: Null<string>;
    Same?: Null<string>;
    City?: string;
    Mail?: string;
    Last?: string;
    First?: string;
    Image?: Null<string>;
    Phone?: string;
    Suite?: string;
    Mobile?: string;
    Status?: number;
    Address?: string;
    Contact?: Null<string>;
    Comment?: Null<string>;
    Creation?: Null<Date>;
    Deletion?: Null<Date>;
    Modification?: Null<Date>;
}