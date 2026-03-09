import {useMemo, useState} from "react";

import {
    Outlet,
    useLocation
} from "react-router-dom";

import {
    Box,
    Stack,
    Alert
} from "@mui/material";

import {useApplication} from "./../hooks/Application";

import {useSession} from "./../hooks/Session";

import {Side} from "./Side";

import {Head} from "./Head";

import {MENU} from "./../environment";

export const Page = ({title}: {
    title: string;
}) => {
    const {side, message, setMessage} = useApplication();

    const {session} = useSession();

    const location = useLocation();

    const menu = useMemo(() => (
        ((task, role, menu, list) => {
            menu.forEach((item) => {
                task(task, role, item, list);
            });

            return list;
        })((task: any, role: any, item: any, list: Array<any>) => {
            if (item.list) {
                let nest:any[] = [];

                item.list.forEach((item: any) => {
                    task(task, role, item, nest);
                });

                console.log('next', nest)

                if (nest.length) {
                    list.push({...item, list: nest});
                }
            } else {
                if ((item.type ? ((item.type instanceof Array) ? item.type.includes(session.type) : (item.type == session.type)) : true)) {
                    if ((item.pass ? role[item.pass] : true)) {
                        list.push(item);
                    }
                }
            }
        }, {}, MENU, [])
    ), [session.type]);

    return (
        <Stack sx={{display: 'flex', minHeight: '100vh'}}>
            <Head />
            <Stack
                flex={1}
                display="flex"
                direction="row">
                <Side
                    path={location.pathname}
                    open={side}
                    menu={menu} />
                <Stack
                    sx={(theme) => ({
                        padding: '24px 24px'
                    })}
                    display="flex"
                    flex={1}>
                    {(Boolean(message) && (
                        <Alert
                            elevation={6}
                            severity={message?.type}
                            variant="filled"
                            sx={{mt: '24px', width: '100%'}}
                            onClose={(event) => {
                                setMessage(null);
                            }}>
                            {message?.text}
                        </Alert>
                    ))}
                    <Box
                        display="flex"
                        flex={1}>
                        <Outlet />
                    </Box>
                </Stack>
            </Stack>
        </Stack>
    );
};