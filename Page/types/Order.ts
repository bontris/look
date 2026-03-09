import type {Null} from "./Null";

import type {Hour} from "./Hour";

import type {Payment} from "./Payment";

export type Order = {
    Id?: Null<number>;
    Ud?: Null<string>;
    TimeId?: Null<number>;
    StateId?: Null<number>;
    NationId?: Null<number>;
    Lock?: Null<boolean>;
    Test?: Null<boolean>;
    Hide?: Null<boolean>;
    Code?: Null<string>;
    Pass?: Null<string>;
    Same?: Null<string>;
    Home?: string;
    Cell?: string;
    City?: string;
    Mail?: string;
    Sign?: string;
    Last?: string;
    First?: string;
    Suite?: string;
    Postal?: string;
    Stage?: number;
    Status?: number;
    Areas?: Null<string>;
    Cities?: Null<string>;
    Slogan?: Null<string>;
    Address?: Null<string>;
    Contact?: Null<string>;
    Services?: Null<string>;
    Products?: Null<string>;
    Comments?: Null<string>;
    Bussiness?: Null<string>;
    Guarantees?: Null<string>;
    Specialties?: Null<string>;
    Description?: Null<string>;
    Creation?: Null<Date>;
    Deletion?: Null<Date>;
    /*Specializations?: Array<{
        Name?: Null<string>
    }>;*/
    Hours?: Array<Hour>,
    Payments?: Array<Payment>,
    Telephones?: Array<{
        Type: 1 | 2,
        Number: Null<string>
    }>,
    Modification?: Null<Date>;Object?:{Last: string, Name: string}
}