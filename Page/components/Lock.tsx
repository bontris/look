import {Suspense, ReactNode} from "react";

import {
    Navigate,
    useLocation
} from "react-router-dom";

import {
    Box,
    CircularProgress
} from "@mui/material";

import {useApplication} from "./../hooks/Application";

import {useSession} from "./../hooks/Session";

export const Lock = ({type, pass, name, children}: {
    type?: number;
    pass?: string;
    name: string;
    children: ReactNode;
}) => {
    const {setPage} = useApplication();

    const {session} = useSession();
    
    if (session.item) {
        //setPage(name);

        return (
            <Suspense fallback={
                <Box
                    display="flex"
                    justifyContent="center"
                    alignItems="center"
                    minHeight="100vh">
                    <CircularProgress size={48} />
                </Box>
            }>
                {children}
            </Suspense>
        );
    }  else {
        return (
            <Navigate
                to="/login"
                state={{from: useLocation()}}
                replace />
        )
    }
}