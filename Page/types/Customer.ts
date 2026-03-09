import type {Null} from "./Null";

import type {Hour} from "./Hour";

import type {Agent} from "./Agent";

import type {Comment} from "./Comment";

import type {Payment} from "./Payment";

export type Customer = {
    Id?: Null<number>;
    Ud?: Null<string>;
    TimeId?: Null<number>,
    StateId?: Null<number>,
    NationId?: Null<number>,
    ManageId?: Null<number>,
    SupportId?: Null<number>,
    Code?: Null<string>,
    Mame?: Null<string>;
    City?: Null<string>,
    Last?: Null<string>,
    First?: Null<string>,
    Suite?: Null<string>,
    Postal?: Null<string>,
    Address?: Null<string>,
    Creation?: Null<Date>,
    Deletion?: Null<Date>,
    Hours?: Array<Hour>,
    Manage?: Null<Agent>,
    Support?: Null<Agent>,
    Comments?: Array<Comment>,
    Payments?: Array<Payment>,
    Telephones?: Array<{
        Type: 1 | 2,
        Number: Null<string>
    }>,
    Modification?: Null<Date>
}