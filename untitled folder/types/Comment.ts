import type {Null} from "./Null";

import type {User} from "./User";

import type {Task} from "./Task";

import type {Customer} from "./Customer";

export type Comment = {
    Id?: Null<number>;
    Ud?: Null<string>;
    UserId?: Null<number>,
    CustomerId?: Null<number>,
    Content?: Null<string>;
    User?: Null<User>,
    Customer?: Null<Customer>,
    Creation?: Null<Date>,
    Deletion?: Null<Date>,
    Modification?: Null<Date>,
    Tasks?: Array<Task>
}