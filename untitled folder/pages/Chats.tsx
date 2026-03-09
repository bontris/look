import React, {useRef, useState, useEffect, Fragment, RefObject} from "react";

import moment from "moment";

import {
    useParams,
    useNavigate
} from "react-router-dom";

import ReactMarkdown from 'react-markdown'

import remarkGfm from "remark-gfm";

import {Type} from "./../components/Type";

import {
    Box,
    Chip,
    Card,
    Grid,
    Menu,
    Link,
    alpha,
    Stack,
    Paper,
    Alert,
    Table,
    AppBar,
    Dialog,
    Drawer,
    Avatar,
    Button,
    lighten,
    Divider,
    Toolbar,
    SvgIcon,
    Backdrop,
    Collapse,
    Snackbar,
    MenuItem,
    ListItem,
    TableRow,
    TableCell,
    TableBody,
    InputBase,
    TextField,
    CardHeader,
    Typography,
    IconButton,
    DialogTitle,
    FormControl,
    ListItemIcon,
    ListItemText,
    DialogContent,
    DialogActions,
    ListSubheader,
    ListItemAvatar,
    ListItemButton,
    InputAdornment,
    CircularProgress
} from "@mui/material";

import {BACK} from "./../environment";

import {useApplication} from "../hooks/Application";

import {useSession} from "../hooks/Session";

import type {Null} from "../types/Null";

import type {Hash} from "../types/Hash";

import {Fail} from "../components/Fail";

import {Load} from "../components/Load";

import type {User} from "../types/User";

import {Data} from "./../components/Data";

import {Form} from "./../components/Form";

import Store from "./../services/Makes";

export namespace Chats {
    export const Main = ({task}: {task?: string}) => {
        const {
            setAlert,
            setTitle,
            setDialog
        } = useApplication();

        const {session, help} = useSession();

        const navigate = useNavigate();

        const [take, setTake] = useState(16);

        const [page, setPage] = useState(1);

        const [chat, setChat] = useState<{
            node: Null<RefObject<HTMLDivElement>>,
            list: Array<Hash<any>>, 
            wait: boolean,
            text: string
        }>({
            node: useRef<HTMLDivElement>(null),
            wait: false,
            list: [],
            text: ''
        });

        useEffect(() => {
            chat.node?.current?.scroll({top: chat.node?.current.scrollHeight, behavior: 'smooth'});
        }, [chat.list]);
  
        return (
            <Grid
                direction="column"
                display="flex"
                spacing={2}
                container>
                <Grid item>
                    <Typography
                        color="text.primary"
                        variant="h4"
                        gutterBottom>
                        Valentina
                    </Typography>
                    <Typography
                        color="text.secondary"
                        gutterBottom>
                        Abogada IA de Taller A.
                    </Typography>
                </Grid>
                <Grid
                    display="flex"
                    flex={1}
                    item>
                    <Stack
                        display="flex"
                        flex={1}>
                        <Stack
                            flex={1}
                            ref={chat.node}
                            sx={{position: 'relative', overflow: 'auto'}}>
                            <Stack
                                gap={1}
                                sx={{width: '100%', height: 'auto', padding: 4, position: 'absolute', overflow: 'auto', boxSizing: 'border-box'}}>
                                {chat.list.map((next) => (
                                    <Stack
                                        alignItems={(next.left ? 'start': 'end')}
                                        key={next.item}>
                                        <Stack
                                            sx={(theme) => ({
                                                px: 2,
                                                py: 1,
                                                color: next.left ? 'text.primary' : 'common.white',
                                                fontFamily: theme.typography.fontFamily,
                                                maxWidth: '90%',
                                                borderRadius: '6px',
                                                position: 'relative',
                                                background: next.left ? theme.palette.background.paper : theme.palette.primary.main,
                                                '&:after': {
                                                    top: 0,
                                                    left: next.left ? '-15px' : 'auto',
                                                    right: next.left ? 'auto' : '-15px',
                                                    width: 0,
                                                    height: 0,
                                                    content: "''",
                                                    position: 'absolute',
                                                    borderTop: `15px solid ${next.left ? theme.palette.background.paper : theme.palette.primary.main}`,
                                                    borderLeft: '15px solid transparent',
                                                    borderRight: '15px solid transparent'
                                                }
                                            })}>
                                            <ReactMarkdown
                                                remarkPlugins={[[remarkGfm, {singleTilde: false}]]}
                                                components={{
                                                    code: ({className, children, ...rest}) => {
                                                        return ((/language-(\w+)/.exec((className ?? ''))) ? (
                                                                <Box
                                                                    component="code"
                                                                    sx={(theme) => ({
                                                                        borderRadius: 1,
                                                                        background: theme.palette.background.default,
                                                                        padding: 2
                                                                    })}>
                                                                    {children}
                                                                </Box>
                                                            ) : (
                                                                <code>
                                                                    {children}
                                                                </code>
                                                            )
                                                        )  
                                                    }
                                                }}>
                                                {(next.text instanceof Object ? JSON.stringify(next.text) : next.text)}
                                            </ReactMarkdown>
                                        </Stack>
                                        <Stack
                                            sx={{pt: 1}}
                                            gap={1}
                                            direction={(next.left ? 'row' : 'row-reverse')}
                                            alignItems="center">
                                            <IconButton>
                                                <SvgIcon sx={{width: 18, height: 18}}>
                                                    <g
                                                        strokeLinecap="round"
                                                        strokeLinejoin="round"
                                                        strokeWidth="2"
                                                        stroke="currentColor"
                                                        fill="none">
                                                        <path d="M7 9.667A2.667 2.667 0 0 1 9.667 7h8.666A2.667 2.667 0 0 1 21 9.667v8.666A2.667 2.667 0 0 1 18.333 21H9.667A2.667 2.667 0 0 1 7 18.333z" />
                                                        <path d="M4.012 16.737A2 2 0 0 1 3 15V5c0-1.1.9-2 2-2h10c.75 0 1.158.385 1.5 1" />
                                                    </g>
                                                </SvgIcon>
                                            </IconButton>
                                            {(next.left && (
                                                <IconButton>
                                                    <SvgIcon sx={{width: 18, height: 18}}>
                                                        <g
                                                            strokeLinecap="round"
                                                            strokeLinejoin="round"
                                                            strokeWidth="2"
                                                            stroke="currentColor"
                                                            fill="none">
                                                            <path d="M7 11v8a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1v-7a1 1 0 0 1 1-1za4 4 0 0 0 4-4V6a2 2 0 0 1 4 0v5h3a2 2 0 0 1 2 2l-1 5a2 3 0 0 1-2 2h-7a3 3 0 0 1-3-3" />
                                                        </g>
                                                    </SvgIcon>
                                                </IconButton>
                                            ))}
                                            {(next.left && (
                                                <IconButton>
                                                    <SvgIcon sx={{width: 18, height: 18}}>
                                                        <g
                                                            strokeLinecap="round"
                                                            strokeLinejoin="round"
                                                            strokeWidth="2"
                                                            stroke="currentColor"
                                                            fill="none">
                                                            <path d="M7 13V5a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v7a1 1 0 0 0 1 1za4 4 0 0 1 4 4v1a2 2 0 0 0 4 0v-5h3a2 2 0 0 0 2-2l-1-5a2 3 0 0 0-2-2h-7a3 3 0 0 0-3 3" />
                                                        </g>
                                                    </SvgIcon>
                                                </IconButton>
                                            ))}
                                            {(next.left && (
                                                <IconButton>
                                                    <SvgIcon sx={{width: 18, height: 18}}>
                                                        <g
                                                            strokeLinecap="round"
                                                            strokeLinejoin="round"
                                                            strokeWidth="2"
                                                            stroke="currentColor"
                                                            fill="none">
                                                            <path d="M19.933 13.041a8 8 0 1 1-9.925-8.788c3.899-1 7.935 1.007 9.425 4.747" />
                                                            <path d="M20 4v5h-5" />
                                                        </g>
                                                    </SvgIcon>
                                                </IconButton>
                                            ))}
                                        </Stack>
                                    </Stack>
                                ))}
                            </Stack>
                        </Stack>
                        <Stack sx={{px: 4, pb: 4}}>
                            <Paper sx={{padding: 1}}>
                                <Type
                                    lock={chat.wait}
                                    text={chat.text}
                                    send={async (text: string, file: Null<File>) => {
                                        setChat((last) => ({
                                            ...last,
                                            text: '',
                                            wait: true,
                                            list: [...last.list, {
                                                date: moment().format('YYYY-MM-DD hh:mm:ss'),
                                                item: last.list.length,
                                                left: false,
                                                text: text
                                            }]
                                        }));

                                        const data = await help(text);

                                        if (data.done) {
                                            setChat((last) => ({
                                                ...last,
                                                wait: false,
                                                list: [...last.list, {
                                                    date: moment().format('YYYY-MM-DD hh:mm:ss'),
                                                    item: last.list.length,
                                                    text: data.text,
                                                    left: true
                                                }]
                                            }));
                                        } else {
                                            setChat((last) => ({
                                                ...last,
                                                wait: false
                                            }));
                                        }
                                    }}>
                                    {(chat.list.length ? `Responder...` : 'Cómo te puedo ayudar hoy?')}
                                </Type>
                            </Paper>
                        </Stack>
                    </Stack>
                </Grid>
            </Grid>
        );
    }
}