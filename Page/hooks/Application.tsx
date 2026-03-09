import {useContext} from "react";

import {ApplicationContext} from "../contexts/Application";

export const useApplication = () => {
    const context = useContext(ApplicationContext);

    if (context) {
        return context;
    }

    throw new Error('ApplicationContext must be used within a ApplicationProvider');
}