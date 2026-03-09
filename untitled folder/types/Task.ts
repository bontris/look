import type {Null} from "./Null";

import type {User} from "./User";

import type {Assignment} from "./Assignment";

export type Task = {
    Id?: Null<number>
    Ud?: Null<string>
    UserId?: Null<number>
    ObjectId?: Null<number>
    Type?: Null<0|1|2>
    Status?: Null<0|1|2>
    Time?: Null<string>
    Date?: Null<string>
    Title?: Null<string>
    Comment?: Null<string>
    Creation?: Null<Date>
    Deletion?: Null<Date>
    Modification?: Null<Date>
    User?: Null<User>
    Assigments?: Array<Assignment>
}