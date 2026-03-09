import {ReactNode, useState, useEffect, createContext} from "react";

import {useCookies} from "react-cookie";

export type Settings = {
    text: 'english' | 'spanish' | 'french',
    mode: 'system' | 'light' | 'dark',
    skin: boolean,
    side: boolean
}

export const SettingsProvider = ({children}: {
    children: ReactNode
}) => {
    //const {setMode} = useColorScheme();

    const [cookies, setCookie] = useCookies([
        'mode',
        'skin',
        'side',
        'code',
        'text'
    ]);

    const [settings, setSettings] = useState<Settings>({
        text: cookies.text ?? 'english',
        mode: cookies.mode ?? 'light',
        skin: Boolean(cookies.skin),
        side: Boolean(cookies.side)
    });

    const setSetting = (name: keyof Settings, data: any) => {
        setSettings((last) => ({...last, [name]: data}));

        setCookie(name, data);
    }

    useEffect(() => {console.log('mode',settings.mode)
        //setMode(settings.mode);
    }, [settings.mode])

    return (
        <SettingsContext.Provider value={{
            setSetting,
            settings
        }}>
            {children}
        </SettingsContext.Provider>
    )
}

export const SettingsContext = createContext<{
    setSetting: (name: keyof Settings, data: any) => void,
    settings: Settings
} | null>(null)