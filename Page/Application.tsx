import {useState, useEffect} from "react";

import {
    Route,
    RouterProvider,
    createBrowserRouter,
    createRoutesFromElements
} from "react-router-dom";

import {
    Box,
    CircularProgress
} from "@mui/material";

import {ApplicationProvider} from "./contexts/Application";

import {SettingsProvider} from "./contexts/Settings";

import {CookiesProvider} from "react-cookie";

import {useSession} from "./hooks/Session";

import {Page} from "./components/Page";

import {Lock} from "./components/Lock";

import {Sign} from "./pages/Sign";

import {Dash} from "./pages/Dash";

import {Terms} from "./pages/Terms";

import {Loads} from "./pages/Loads";

import {Tasks} from "./pages/Tasks";

import {Tests} from "./pages/Tests";

import {Signs} from "./pages/Signs";

import {Pasts} from "./pages/Pasts";

import {Makes} from "./pages/Makes";

import {Cards} from "./pages/Cards";

import {Leads} from "./pages/Leads";

import {Hands} from "./pages/Hands";

import {Firms} from "./pages/Firms";

import {Files} from "./pages/Files";

import {Chats} from "./pages/Chats";

import {Rings} from "./pages/Rings";

import {Kinds} from "./pages/Kinds";

import {Sales} from "./pages/Sales";

export const Application = () => {
    const {ping} = useSession();

    const [done, setDone] = useState(false);

    const router = createBrowserRouter(
        createRoutesFromElements(
            <Route>
                <Route 
                    element={<Sign name="" pass="" />}
                    path="/login" />
                <Route element={<Page title="Page" />}>
                    <Route
                        element={
                            <Lock name="Dash">
                                <Dash />
                            </Lock>
                        }
                        index/>
                    <Route path="/gacetas">
                        <Route
                            element={
                                <Lock name="Terms.List">
                                    <Terms.List />
                                </Lock>
                            }
                            index />
                        <Route
                            element={
                                <Lock name="Terms.View">
                                    <Terms.View />
                                </Lock>
                            }
                            path="view/:item" />
                    </Route>
                    <Route path="/documentos">
                        <Route
                            element={
                                <Lock name="Files.List">
                                    <Files.List />
                                </Lock>
                            }
                            index />
                        <Route
                            element={
                                <Lock name="Files.View">
                                    <Files.View />
                                </Lock>
                            }
                            path=":item" />
                    </Route>
                    <Route path="/notificaciones">
                        <Route
                            element={
                                <Lock name="Rings.List">
                                    <Rings.List />
                                </Lock>
                            }
                            index />
                        <Route
                            element={
                                <Lock name="Rings.Make">
                                    <Rings.Make />
                                </Lock>
                            }
                            path="make" />
                        <Route
                            element={
                                <Lock name="Rings.View">
                                    <Rings.View />
                                </Lock>
                            }
                            path="view/:item" />
                        <Route
                            element={
                                <Lock name="Rings.Edit">
                                    <Rings.Edit />
                                </Lock>
                            }
                            path="edit/:item" />
                    </Route>
                    <Route path="/anuncios">
                        <Route
                            element={
                                <Lock name="Cards.List">
                                    <Cards.List />
                                </Lock>
                            }
                            index />
                        <Route
                            element={
                                <Lock name="Cards.Make">
                                    <Cards.Make />
                                </Lock>
                            }
                            path="make" />
                        <Route
                            element={
                                <Lock name="Cards.View">
                                    <Cards.View />
                                </Lock>
                            }
                            path="view/:item" />
                        <Route
                            element={
                                <Lock name="Leads.Edit">
                                    <Cards.Edit />
                                </Lock>
                            }
                            path="edit/:item" />
                    </Route>
                    <Route path="/clientes">
                        <Route
                            element={
                                <Lock name="Leads.List">
                                    <Leads.List />
                                </Lock>
                            }
                            index />
                        <Route
                            element={
                                <Lock name="Leads.Make">
                                    <Leads.Make />
                                </Lock>
                            }
                            path="make" />
                        <Route
                            element={
                                <Lock name="Leads.View">
                                    <Leads.View />
                                </Lock>
                            }
                            path="view/:item" />
                        <Route
                            element={
                                <Lock name="Leads.Edit">
                                    <Leads.Edit />
                                </Lock>
                            }
                            path="edit/:item" />
                    </Route>
                    <Route path="/abogado">
                        <Route
                            element={
                                <Lock name="Chats.Main">
                                    <Chats.Main />
                                </Lock>
                            }
                            index />
                    </Route>
                    <Route path="/equipo">
                        <Route
                            element={
                                <Lock name="Hands.List">
                                    <Hands.List />
                                </Lock>
                            }
                            index />
                        <Route
                            element={
                                <Lock name="Hands.Make">
                                    <Hands.Make />
                                </Lock>
                            }
                            path="make" />
                        <Route
                            element={
                                <Lock name="Hands.View">
                                    <Hands.View />
                                </Lock>
                            }
                            path="view/:item" />
                        <Route
                            element={
                                <Lock name="Hands.Edit">
                                    <Hands.Edit />
                                </Lock>
                            }
                            path="edit/:item" />
                    </Route>
                    <Route path="/empresas">
                        <Route
                            element={
                                <Lock name="Firms.List">
                                    <Firms.List />
                                </Lock>
                            }
                            index />
                        <Route
                            element={
                                <Lock name="Firms.Make">
                                    <Firms.Make />
                                </Lock>
                            }
                            path="make" />
                        <Route
                            element={
                                <Lock name="Firms.View">
                                    <Firms.View />
                                </Lock>
                            }
                            path="view/:item" />
                        <Route
                            element={
                                <Lock name="Firms.Edit">
                                    <Firms.Edit />
                                </Lock>
                            }
                            path="edit/:item" />
                    </Route>
                    <Route path="/ventas">
                        <Route
                            element={
                                <Lock name="Sales.List">
                                    <Sales.List />
                                </Lock>
                            }
                            index />
                        <Route
                            element={
                                <Lock name="Sales.Make">
                                    <Sales.Make />
                                </Lock>
                            }
                            path="make" />
                        <Route
                            element={
                                <Lock name="Sales.View">
                                    <Sales.View />
                                </Lock>
                            }
                            path="view/:item" />
                        <Route
                            element={
                                <Lock name="Sales.Edit">
                                    <Sales.Edit />
                                </Lock>
                            }
                            path="edit/:item" />
                    </Route>
                    <Route path="/clases">
                        <Route
                            element={
                                <Lock name="Kinds.List">
                                    <Kinds.List />
                                </Lock>
                            }
                            index />
                        <Route
                            element={
                                <Lock name="Kinds.Make">
                                    <Kinds.Make />
                                </Lock>
                            }
                            path="make" />
                        <Route
                            element={
                                <Lock name="Kinds.View">
                                    <Kinds.View />
                                </Lock>
                            }
                            path="view/:item" />
                        <Route
                            element={
                                <Lock name="Kinds.Edit">
                                    <Kinds.Edit />
                                </Lock>
                            }
                            path="edit/:item" />
                    </Route>
                    <Route path="/informes">
                        <Route
                            element={
                                <Lock name="Loads.List">
                                    <Loads.List />
                                </Lock>
                            }
                            index />
                        <Route
                            element={
                                <Lock name="Loads.View">
                                    <Loads.View />
                                </Lock>
                            }
                            path=":item" />
                    </Route>
                    <Route path="/servicios">
                        <Route
                            element={
                                <Lock name="Tasks.List">
                                    <Tasks.List />
                                </Lock>
                            }
                            index />
                        <Route
                            element={
                                <Lock name="Tasks.View">
                                    <Tasks.View />
                                </Lock>
                            }
                            path=":item" />
                        <Route
                            element={
                                <Lock name="Tasks.Make">
                                    <Tasks.Make />
                                </Lock>
                            }
                            path="make" />
                    </Route>
                    <Route path="/firmas/:type">
                        <Route
                            element={
                                <Lock name="Signs.List">
                                    <Signs.List />
                                </Lock>
                            }
                            index />
                        <Route
                            element={
                                <Lock name="Signs.Make">
                                    <Signs.Make />
                                </Lock>
                            }
                            path="make" />
                        <Route
                            element={
                                <Lock name="Signs.View">
                                    <Signs.View />
                                </Lock>
                            }
                            path=":item" />
                    </Route>
                    <Route path="/validaciones/:type">
                        <Route
                            element={
                                <Lock name="Pasts.List">
                                    <Pasts.List />
                                </Lock>
                            }
                            index />
                        <Route
                            element={
                                <Lock name="Pasts.View">
                                    <Pasts.View />
                                </Lock>
                            }
                            path=":item" />
                    </Route>
                    <Route path="/vigilancia">
                        <Route
                            element={
                                <Lock name="Makes.List">
                                    <Makes.List />
                                </Lock>
                            }
                            index />
                        <Route
                            element={
                                <Lock name="Makes.Make">
                                    <Makes.Make />
                                </Lock>
                            }
                            path="make" />
                        <Route
                            element={
                                <Lock name="Makes.View">
                                    <Makes.View />
                                </Lock>
                            }
                            path="view/:item/:type?" />
                        <Route
                            element={
                                <Lock name="Makes.Seek">
                                    <Makes.Seek />
                                </Lock>
                            }
                            path="seek/:item?/:load" />
                        <Route
                            element={
                                <Lock name="Makes.Edit">
                                    <Makes.Edit />
                                </Lock>
                            }
                            path="edit/:item" />
                    </Route>
                    <Route path="/diagnosticos">
                        <Route
                            element={
                                <Lock name="Tests.List">
                                    <Tests.List />
                                </Lock>
                            }
                            index />
                        <Route
                            element={
                                <Lock name="Tests.Make">
                                    <Tests.Make />
                                </Lock>
                            }
                            path="make" />
                        <Route
                            element={
                                <Lock name="Tests.View">
                                    <Tests.View />
                                </Lock>
                            }
                            path="view/:item" />
                        <Route
                            element={
                                <Lock name="Tests.Edit">
                                    <Tests.Edit />
                                </Lock>
                            }
                            path="edit/:item" />
                    </Route>
                </Route>
            </Route>
        )
    );

    useEffect(() => {
        const pass = localStorage.getItem('pass');

        if (pass) {
            (async (pass) => {
                await ping(pass);

                setDone(true);
            })(pass);
        } else {
            setDone(true);
        }
    }, []);

    return (
        <CookiesProvider>
            <SettingsProvider>
                <ApplicationProvider>
                    {(done ? (
                        <RouterProvider router={router} />
                    ) : (
                        <Box
                            justifyContent="center"
                            alignItems="center"
                            minHeight="100vh"
                            display="flex">
                            <CircularProgress size={48} />
                        </Box>
                    ))}
                </ApplicationProvider>
            </SettingsProvider>
        </CookiesProvider>
    )
}