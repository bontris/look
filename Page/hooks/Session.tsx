import {useContext} from "react";

import {SessionContext} from "./../contexts/Session";

export const useSession = () => {
    const context = useContext(SessionContext);

    if (context) {
        return context;
    }

    throw new Error('SessionContext must be used within a SessionProvider');
}