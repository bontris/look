import {useContext} from "react";

import {SettingsContext} from "./../contexts/Settings";

export const useSettings = () => {
    const context = useContext(SettingsContext);

    if (context) {
        return context;
    }

    throw new Error('SettingsContext must be used within a SettingsProvider');
}