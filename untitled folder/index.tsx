import {StrictMode} from "react";

import {createRoot} from "react-dom/client";

import {SessionProvider} from "./contexts/Session";

import {Application} from "./Application";

const root = createRoot(document.getElementById('root') as HTMLElement);

root.render(
    //<StrictMode>
        <SessionProvider>
            <Application />
        </SessionProvider>
    //</StrictMode>
);