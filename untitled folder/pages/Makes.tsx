import React, {useRef, useMemo, useState, useEffect, Fragment} from "react";

import {useReactToPrint} from "react-to-print";

import Excel from "exceljs";

import moment from "moment";

import {
    useParams,
    useNavigate
} from "react-router-dom";

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

import Kinds from "./../services/Kinds";

export namespace Makes {
    export const List = ({task}: {task?: string}) => {
        const {
            setAlert,
            setTitle,
            setDialog
        } = useApplication();

        const {session} = useSession();

        const navigate = useNavigate();

        const [take, setTake] = useState(16);

        const [page, setPage] = useState(1);

        const node = useRef<HTMLDivElement>(null);
                
        const dump = useReactToPrint({contentRef: node, documentTitle: 'Informe'});
        

        const [data, setData] = useState<{
            pipe: Hash<{data: Null<any>, sign: '~' | '=' | '<' | '>' | '!'}>,
            list: Array<Hash<any>>,
            item: Null<Hash<any>>,
            find: Null<string>,
            form: Hash<any>,
            heap: Hash<any>,
            sort: Hash<any>,
            wait: boolean,
            lock: boolean,
            take: number,
            page: number,
            size: number,
            time: number
        }>({
            wait: false,
            lock: false,
            item: null,
            list: [],
            pipe: {},
            sort: {},
            take: 16,
            page: 0,
            size: 0,
            time: 0,
            find: '',
            heap: {
                date: {
                    wait: false,
                    fail: false,
                    take: 0,
                    size: 0,
                    list: []
                }
            },
            form: {
                note: {
                    wait: false,
                    open: false,
                    item: null,
                    text: null,
                    list: null,
                    data: {
                        term: null,
                        rate: null,
                        rank: null,
                        text: null,
                        case: null,
                        file: null
                    }
                },
                bulk: {
                    wait: false,
                    open: false,
                    item: null,
                    text: null,
                    list: null,
                    data: {
                        file: null
                    }
                },
                risk: {
                    open: false,
                    wait: false,
                    done: false,
                    fail: null,
                    data: null
                },
                find: {
                    wait: false,
                    open: false,
                    data: {
                        item: null
                    }
                }
            }
        });

        const type = useMemo(() => (
            {
                hint: 'Status',
                pick: data.pipe.pick?.data,
                list: [
                    {
                        item: null,
                        text: 'Todos'
                    },
                    {
                        item: 1,
                        text: 'Negada'
                    },
                    {
                        item: 2,
                        text: 'Publicada'
                    },
                    {
                        item: 3,
                        text: 'Registrada'
                    },
                    {
                        item: 4,
                        text: 'Bajo examen de fondo'
                    },
                    {
                        item: 5,
                        text: 'Cancelada'
                    },
                    {
                        item: 6,
                        text: 'Renuncia total'
                    },
                    {
                        item: 7,
                        text: 'Concepto de viabilidad'
                    },
                    {
                        item: 8,
                        text: 'Bajo examen de forma'
                    },
                    {
                        item: 9,
                        text: 'Con oposición'
                    },
                    {
                        item: 10,
                        text: 'Caducada'
                    }
                ],
                task: (pick: any) => {
                    setData((last) => ({
                        ...last,
                        type: pick,
                        pipe: {
                            ...last.pipe,
                            rank: {
                                data: pick,
                                sign: '='
                            }
                        }
                    }));
                }
            }
        ), [data.pipe.rank?.data]);

        useEffect(() => {
            Store.load(data.take, data.page, data.find, Object.keys(data.pipe).filter((name) => ((typeof data.pipe[name]['data'] == 'number') || Boolean(data.pipe[name]['data']))).map((name) => {
                return `${name}: ${(data.pipe[name]['data'] instanceof Array ? data.pipe[name]['data'].join(' ') : data.pipe[name]['data'])}`
            }, []).join(', '), null, data.sort, (done, data) => {
                if (done) {
                    setData((last) => ({...last, wait: false, page: data.page, list: data.data, size: data.size}));
                } else {
                    //setNote({show: true, type: 'error', text: 'No se pudo cargar los registros.'});

                    setData((last) => ({...last, list: [], wait: false}));
                }
            });
        }, [data.page, data.take, data.sort, data.find, data.pipe, data.time]);
  
        return (
            <Fragment>
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
                            Vigilancia de marcas
                        </Typography>
                        <Typography
                            color="text.secondary"
                            gutterBottom>
                            Módulo de manejo de marcas
                        </Typography>
                    </Grid>
                    <Grid
                        display="flex"
                        flex={1}
                        item>
                        <Data
                            seek={'Id'}
                            name=""
                            type={type}
                            menu={[
                                {
                                    type: 'push',
                                    name: 'bulk',
                                    show: ([1, 3].includes(session.type)),
                                    view: (lock: boolean) => (
                                        <Button
                                            sx={{padding: '2px 6px 2px 16px'}}
                                            color="primary"
                                            variant="contained"
                                            disabled={lock}
                                            startIcon={(
                                                <SvgIcon sx={{width: '24px', height: '24px'}}>
                                                    <g
                                                        strokeLinejoin="round"
                                                        strokeLinecap="round"
                                                        strokeWidth="2"
                                                        stroke="currentColor"
                                                        fill="none">
                                                        <path d="M4 6c0 1.657 3.582 3 8 3s8-1.343 8-3s-3.582-3-8-3s-8 1.343-8 3" />
                                                        <path d="M4 6v6c0 1.657 3.582 3 8 3c.856 0 1.68-.05 2.454-.144M20 12V6" />
                                                        <path d="M4 12v6c0 1.657 3.582 3 8 3q.256 0 .51-.006M19 22v-6m3 3l-3-3l-3 3" />
                                                    </g>
                                                </SvgIcon>
                                            )}
                                            endIcon={(
                                                <SvgIcon sx={{width: '38px', height: '38px'}}>
                                                    <g
                                                        strokeLinejoin="round"
                                                        strokeLinecap="round"
                                                        strokeWidth="2"
                                                        stroke="none"
                                                        fill="currentColor">
                                                        <path d="M17 3.34a10 10 0 1 1-14.995 8.984L2 12l.005-.324A10 10 0 0 1 17 3.34M12.02 7l-.163.01l-.086.016l-.142.045l-.113.054l-.07.043l-.095.071l-.058.054l-4 4l-.083.094a1 1 0 0 0 1.497 1.32L11 10.414V16l.007.117A1 1 0 0 0 13 16v-5.585l2.293 2.292l.094.083a1 1 0 0 0 1.32-1.497l-4-4l-.082-.073l-.089-.064l-.113-.062l-.081-.034l-.113-.034l-.112-.02z" />
                                                    </g>
                                                </SvgIcon>
                                            )}
                                            onClick={(event) => {
                                                setData((last) => ({
                                                    ...last,
                                                    form: {
                                                        ...last.form,
                                                        bulk: {
                                                            ...last.form.bulk,
                                                            open: true,
                                                            text: null,
                                                            list: null,
                                                            data: {
                                                                firm: null,
                                                                file: null
                                                            }
                                                        }
                                                    }
                                                }));
                                            }}>
                                            Importar
                                        </Button>
                                    )
                                },
                                {
                                    type: 'push',
                                    name: 'make',
                                    show: ([1, 3].includes(session.type)),
                                    view: (lock: boolean) => (
                                        <Button
                                            sx={{padding: '2px 6px 2px 16px'}}
                                            color="primary"
                                            variant="contained"
                                            disabled={lock}
                                            startIcon={(
                                                <SvgIcon sx={{width: '24px', height: '24px'}}>
                                                    <g
                                                        strokeLinejoin="round"
                                                        strokeLinecap="round"
                                                        strokeWidth="2"
                                                        stroke="currentColor"
                                                        fill="none">
                                                        <path d="M4 6c0 1.657 3.582 3 8 3s8-1.343 8-3s-3.582-3-8-3s-8 1.343-8 3" />
                                                        <path d="M4 6v6c0 1.657 3.582 3 8 3c1.075 0 2.1-.08 3.037-.224M20 12V6" />
                                                        <path d="M4 12v6c0 1.657 3.582 3 8 3q.249 0 .495-.006M16 19h6m-3-3v6" />
                                                    </g>
                                                </SvgIcon>
                                            )}
                                            endIcon={(
                                                <SvgIcon sx={{width: '38px', height: '38px'}}>
                                                    <g
                                                        strokeLinejoin="round"
                                                        strokeLinecap="round"
                                                        strokeWidth="2"
                                                        stroke="none"
                                                        fill="currentColor">
                                                        <path d="M4.929 4.929A10 10 0 1 1 19.07 19.07A10 10 0 0 1 4.93 4.93zM13 9a1 1 0 1 0-2 0v2H9a1 1 0 1 0 0 2h2v2a1 1 0 1 0 2 0v-2h2a1 1 0 1 0 0-2h-2z" />
                                                    </g>
                                                </SvgIcon>
                                            )}
                                            onClick={(event) => {
                                                navigate(`/vigilancia/make`);
                                            }}>
                                            Agregar
                                        </Button>
                                    )
                                },
                                {
                                    type: 'push',
                                    name: 'seek',
                                    show: ([1, 3].includes(session.type)),
                                    view: (lock: boolean) => (
                                        <Button
                                            sx={{padding: '2px 6px 2px 16px'}}
                                            color="primary"
                                            variant="contained"
                                            disabled={lock}
                                            startIcon={(
                                                <SvgIcon sx={{width: '24px', height: '24px'}}>
                                                    <g
                                                        strokeLinejoin="round"
                                                        strokeLinecap="round"
                                                        strokeWidth="2"
                                                        stroke="currentColor"
                                                        fill="none">
                                                        <path d="M4 6c0 1.657 3.582 3 8 3s8-1.343 8-3s-3.582-3-8-3s-8 1.343-8 3" />
                                                        <path d="M4 6v6c0 1.657 3.582 3 8 3m8-3.5V6" />
                                                        <path d="M4 12v6c0 1.657 3.582 3 8 3m3-3a3 3 0 1 0 6 0a3 3 0 1 0-6 0m5.2 2.2L22 22" />
                                                    </g>
                                                </SvgIcon>
                                            )}
                                            endIcon={(
                                                <SvgIcon sx={{width: '38px', height: '38px'}}>
                                                    <g
                                                        strokeLinejoin="round"
                                                        strokeLinecap="round"
                                                        strokeWidth="2"
                                                        stroke="none"
                                                        fill="currentColor">
                                                        <path d="m12 2l.324.005a10 10 0 1 1-.648 0zm.613 5.21a1 1 0 0 0-1.32 1.497L13.584 11H8l-.117.007A1 1 0 0 0 8 13h5.584l-2.291 2.293l-.083.094a1 1 0 0 0 1.497 1.32l4-4l.073-.082l.064-.089l.062-.113l.044-.11l.03-.112l.017-.126L17 12l-.007-.118l-.029-.148l-.035-.105l-.054-.113l-.071-.111a1 1 0 0 0-.097-.112l-4-4z" />
                                                    </g>
                                                </SvgIcon>
                                            )}
                                            onClick={(event) => {
                                                Store.date((done, data) => {
                                                    if (done) {
                                                        setData((last) => ({
                                                            ...last,
                                                            heap: {
                                                                ...last.heap,
                                                                date: {
                                                                    ...last.heap.date,
                                                                    list: data.data?.map((next: any) => (
                                                                        {item: next.item, text: `${next.post} (${moment(next.date).format('DD/MM/YY')})`}
                                                                    ))
                                                                }
                                                            },
                                                            form: {
                                                                ...last.form,
                                                                find: {
                                                                    ...last.form.find,
                                                                    open: true,
                                                                    wait: false,
                                                                    data: {item: null}
                                                                }
                                                            }
                                                        }));
                                                        
                                                    }
                                                });
                                            }}>
                                            Consultar
                                        </Button>
                                    )
                                }
                            ]}
                            dash={[
                                {
                                    icon: ['M13 5h8m-8 4h5m-5 6h8m-8 4h5M3 5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1zm0 10a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1z'],
                                    hint: 'Detalles',
                                    name: 'View',
                                    view: (item: any, lock: boolean) => (
                                        <Button
                                            sx={{padding: '2px 6px 2px 16px'}}
                                            color="primary"
                                            variant="contained"
                                            disabled={lock}
                                            startIcon={(
                                                <SvgIcon sx={{width: '24px', height: '24px'}}>
                                                    <g
                                                        strokeLinejoin="round"
                                                        strokeLinecap="round"
                                                        strokeWidth="2"
                                                        stroke="currentColor"
                                                        fill="none">
                                                        <path d="M13 5h8m-8 4h5m-5 6h8m-8 4h5M3 5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1zm0 10a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1z" />
                                                    </g>
                                                </SvgIcon>
                                            )}
                                            endIcon={(
                                                <SvgIcon sx={{width: '38px', height: '38px'}}>
                                                    <g
                                                        strokeLinejoin="round"
                                                        strokeLinecap="round"
                                                        strokeWidth="2"
                                                        stroke="none"
                                                        fill="currentColor">
                                                        <path d="m12 2l.324.005a10 10 0 1 1-.648 0zm.613 5.21a1 1 0 0 0-1.32 1.497L13.584 11H8l-.117.007A1 1 0 0 0 8 13h5.584l-2.291 2.293l-.083.094a1 1 0 0 0 1.497 1.32l4-4l.073-.082l.064-.089l.062-.113l.044-.11l.03-.112l.017-.126L17 12l-.007-.118l-.029-.148l-.035-.105l-.054-.113l-.071-.111a1 1 0 0 0-.097-.112l-4-4z" />
                                                    </g>
                                                </SvgIcon>
                                            )}
                                            onClick={(event) => {
                                                navigate(`/vigilancia/view/${item.item}`);
                                            }}>
                                            Detalles
                                        </Button>
                                    )
                                },
                                {
                                    icon: ['M13 5h8m-8 4h5m-5 6h8m-8 4h5M3 5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1zm0 10a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1z'],
                                    hint: 'Concepto de viabilidad',
                                    name: 'Risk',
                                    view: (item: any, lock: boolean) => (
                                        <Button
                                            sx={{padding: '2px 6px 2px 16px'}}
                                            color="primary"
                                            variant="contained"
                                            disabled={lock}
                                            startIcon={(
                                                <SvgIcon sx={{width: '24px', height: '24px'}}>
                                                    <g
                                                        strokeLinejoin="round"
                                                        strokeLinecap="round"
                                                        strokeWidth="2"
                                                        stroke="currentColor"
                                                        fill="none">
                                                        <path d="m9 11l3 3l8-8" />
                                                        <path d="M20 12v6a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h9" />
                                                    </g>
                                                </SvgIcon>
                                            )}
                                            endIcon={(
                                                <SvgIcon sx={{width: '38px', height: '38px'}}>
                                                    <g
                                                        strokeLinejoin="round"
                                                        strokeLinecap="round"
                                                        strokeWidth="2"
                                                        stroke="none"
                                                        fill="currentColor">
                                                        <path d="m12 2l.324.005a10 10 0 1 1-.648 0zm.613 5.21a1 1 0 0 0-1.32 1.497L13.584 11H8l-.117.007A1 1 0 0 0 8 13h5.584l-2.291 2.293l-.083.094a1 1 0 0 0 1.497 1.32l4-4l.073-.082l.064-.089l.062-.113l.044-.11l.03-.112l.017-.126L17 12l-.007-.118l-.029-.148l-.035-.105l-.054-.113l-.071-.111a1 1 0 0 0-.097-.112l-4-4z" />
                                                    </g>
                                                </SvgIcon>
                                            )}
                                            onClick={(event) => {
                                                Store.risk(item.hash, (done: boolean, data: any) => {
                                                    if (done) {
                                                        setData((last) => ({
                                                            ...last,
                                                            form: {
                                                                ...last.form,
                                                                risk: {
                                                                    ...last.form.risk,
                                                                    wait: false,
                                                                    done: true,
                                                                    data: data
                                                                }
                                                            }
                                                        }));
                                                    } else {
                                                        setData((last) => ({
                                                            ...last,
                                                            form: {
                                                                ...last.form,
                                                                risk: {
                                                                    ...last.form.risk,
                                                                    wait: false,
                                                                done: false,
                                                                fail: data?.text
                                                                }
                                                            }
                                                        }));
                                                    }
                                                });

                                                setData((last) => ({
                                                    ...last,
                                                    form: {
                                                        ...last.form,
                                                        risk: {
                                                            ...last.form.risk,
                                                            done: false,
                                                            open: true,
                                                            wait: true,
                                                            fail: null,
                                                            data: null
                                                        }
                                                    }
                                                }));
                                            }}>
                                            Viabilidad
                                        </Button>
                                    )
                                },
                                {
                                    icon: ['M3 10a7 7 0 1 0 14 0a7 7 0 1 0-14 0m18 11l-6-6'],
                                    hint: 'Buscar',
                                    name: 'Find',
                                    show: ([1, 3].includes(session.type)),
                                    view: (item: any, lock: boolean) => (
                                        <Button
                                            sx={{padding: '2px 6px 2px 16px'}}
                                            color="primary"
                                            variant="contained"
                                            disabled={lock}
                                            startIcon={(
                                                <SvgIcon sx={{width: '24px', height: '24px'}}>
                                                    <g
                                                        strokeLinejoin="round"
                                                        strokeLinecap="round"
                                                        strokeWidth="2"
                                                        stroke="currentColor"
                                                        fill="none">
                                                        <path d="M4 6c0 1.657 3.582 3 8 3s8-1.343 8-3s-3.582-3-8-3s-8 1.343-8 3" />
                                                        <path d="M4 6v6c0 1.657 3.582 3 8 3m8-3.5V6" />
                                                        <path d="M4 12v6c0 1.657 3.582 3 8 3m3-3a3 3 0 1 0 6 0a3 3 0 1 0-6 0m5.2 2.2L22 22" />
                                                    </g>
                                                </SvgIcon>
                                            )}
                                            endIcon={(
                                                <SvgIcon sx={{width: '38px', height: '38px'}}>
                                                    <g
                                                        strokeLinejoin="round"
                                                        strokeLinecap="round"
                                                        strokeWidth="2"
                                                        stroke="none"
                                                        fill="currentColor">
                                                        <path d="m12 2l.324.005a10 10 0 1 1-.648 0zm.613 5.21a1 1 0 0 0-1.32 1.497L13.584 11H8l-.117.007A1 1 0 0 0 8 13h5.584l-2.291 2.293l-.083.094a1 1 0 0 0 1.497 1.32l4-4l.073-.082l.064-.089l.062-.113l.044-.11l.03-.112l.017-.126L17 12l-.007-.118l-.029-.148l-.035-.105l-.054-.113l-.071-.111a1 1 0 0 0-.097-.112l-4-4z" />
                                                    </g>
                                                </SvgIcon>
                                            )}
                                            onClick={(event) => {
                                                Store.date((done, data) => {
                                                    if (done) {
                                                        setData((last) => ({
                                                            ...last,
                                                            heap: {
                                                                ...last.heap,
                                                                date: {
                                                                    ...last.heap.date,
                                                                    list: data.data?.map((next: any) => (
                                                                        {item: next.item, text: `${next.post} (${moment(next.date).format('DD/MM/YY')})`}
                                                                    ))
                                                                }
                                                            },
                                                            form: {
                                                                ...last.form,
                                                                find: {
                                                                    ...last.form.find,
                                                                    open: true,
                                                                    wait: false,
                                                                    data: {item: null}
                                                                }
                                                            },
                                                            item: item
                                                        }));
                                                        
                                                    }
                                                });
                                            }}>
                                            Consultar
                                        </Button>
                                    )
                                },
                                {
                                    icon: ['M3 10a7 7 0 1 0 14 0a7 7 0 1 0-14 0m18 11l-6-6'],
                                    hint: 'Editar',
                                    name: 'Edit',
                                    show: ([1, 3].includes(session.type)),
                                    view: (item: any, lock: boolean) => (
                                        <Button
                                            sx={{padding: '2px 6px 2px 16px'}}
                                            color="primary"
                                            variant="contained"
                                            disabled={lock}
                                            startIcon={(
                                                <SvgIcon sx={{width: '24px', height: '24px'}}>
                                                    <g
                                                        strokeLinejoin="round"
                                                        strokeLinecap="round"
                                                        strokeWidth="2"
                                                        stroke="currentColor"
                                                        fill="none">
                                                        <path d="M7 7H6a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2-2v-1" />
                                                        <path d="M20.385 6.585a2.1 2.1 0 0 0-2.97-2.97L9 12v3h3zM16 5l3 3" />
                                                    </g>
                                                </SvgIcon>
                                            )}
                                            endIcon={(
                                                <SvgIcon sx={{width: '38px', height: '38px'}}>
                                                    <g
                                                        strokeLinejoin="round"
                                                        strokeLinecap="round"
                                                        strokeWidth="2"
                                                        stroke="none"
                                                        fill="currentColor">
                                                        <path d="m12 2l.324.005a10 10 0 1 1-.648 0zm.613 5.21a1 1 0 0 0-1.32 1.497L13.584 11H8l-.117.007A1 1 0 0 0 8 13h5.584l-2.291 2.293l-.083.094a1 1 0 0 0 1.497 1.32l4-4l.073-.082l.064-.089l.062-.113l.044-.11l.03-.112l.017-.126L17 12l-.007-.118l-.029-.148l-.035-.105l-.054-.113l-.071-.111a1 1 0 0 0-.097-.112l-4-4z" />
                                                    </g>
                                                </SvgIcon>
                                            )}
                                            onClick={(event) => {
                                                navigate(`/vigilancia/edit/${item.item}`);
                                            }}>
                                            Editar
                                        </Button>
                                    )
                                },
                                {
                                    icon: ['M3 10a7 7 0 1 0 14 0a7 7 0 1 0-14 0m18 11l-6-6'],
                                    hint: 'Agregar comentario',
                                    name: 'Note',
                                    show: ([1, 3].includes(session.type)),
                                    view: (item: any, lock: boolean) => (
                                        <Button
                                            sx={{padding: '2px 6px 2px 16px'}}
                                            color="primary"
                                            variant="contained"
                                            disabled={lock}
                                            startIcon={(
                                                <SvgIcon sx={{width: '24px', height: '24px'}}>
                                                    <g
                                                        strokeLinejoin="round"
                                                        strokeLinecap="round"
                                                        strokeWidth="2"
                                                        stroke="currentColor"
                                                        fill="none">
                                                        <path d="M8 9h8m-8 4h6m-1.99 5.594L8 21v-3H6a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3h12a3 3 0 0 1 3 3v5.5M16 19h6m-3-3v6" />
                                                    </g>
                                                </SvgIcon>
                                            )}
                                            endIcon={(
                                                <SvgIcon sx={{width: '38px', height: '38px'}}>
                                                    <g
                                                        strokeLinejoin="round"
                                                        strokeLinecap="round"
                                                        strokeWidth="2"
                                                        stroke="none"
                                                        fill="currentColor">
                                                        <path d="M4.929 4.929A10 10 0 1 1 19.07 19.07A10 10 0 0 1 4.93 4.93zM13 9a1 1 0 1 0-2 0v2H9a1 1 0 1 0 0 2h2v2a1 1 0 1 0 2 0v-2h2a1 1 0 1 0 0-2h-2z" />
                                                    </g>
                                                </SvgIcon>
                                            )}
                                            onClick={(event) => {
                                                setData((last) => ({
                                                    ...last,
                                                    form: {
                                                        ...last.form,
                                                        note: {
                                                            ...last.form.note,
                                                            open: true,
                                                            text: null,
                                                            list: null,
                                                            item: item,
                                                            data: {
                                                                term: item.term,
                                                                rate: item.rate,
                                                                rank: null,
                                                                text: null,
                                                                case: null,
                                                                file: null
                                                            }
                                                        }
                                                    }
                                                }));
                                            }}>
                                            Comentario
                                        </Button>
                                    )
                                },
                                {
                                    icon: 'M4 7h16m-10 4v6m4-6v6M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2l1-12M9 7V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v3',
                                    tint: '#F84336',
                                    hint: 'Eliminar',
                                    name: 'Drop',
                                    task: (item) => {
                                        setDialog(
                                            <Stack direction="column">
                                                <Typography gutterBottom>
                                                    ¿Deseas eliminar el registro <b>{item.name}{(Boolean(item.card) && ` / ${item.card}`)}</b>?
                                                </Typography>
                                            </Stack>
                                            ,
                                            'Eliminar',
                                            'Eliminar registro de forma permanente.',
                                            'sm',
                                            {
                                                type: 'error',
                                                text: 'Eliminar',
                                                task: (wait: (flag: boolean) => void, hide: (flag: boolean) => void) => {
                                                    Store.drop(item.hash, (done: boolean) => {
                                                        hide(true);

                                                        if (done) {
                                                            setAlert('El registro fue eliminado.', 'success');

                                                            setData((last) => ({
                                                                ...last,
                                                                time: Date.now()
                                                            }));
                                                        } else {
                                                            setAlert('No se pudo eliminar el registro.', 'error');
                                                        }
                                                    });

                                                    wait(true);
                                                }
                                            }
                                        );
                                    }
                                }
                            ]}
                            data={[
                                {
                                    item: 'name',
                                    name: 'Marca',
                                    show: true,
                                    sort: true,
                                    cast: (item: any) => (
                                        <Stack
                                            sx={{alignItems: 'center'}}
                                            spacing={1}
                                            direction="row">
                                            <Avatar
                                                src={(item.icon && `${BACK}/snaps/${item.icon}/thumb`)}
                                                sx={(theme) => ({
                                                    width: 48,
                                                    height: 48,
                                                    background: lighten(theme.palette.primary.main, 0.6)
                                                })}>
                                                {item.name[0]}
                                            </Avatar>
                                            <Stack
                                                sx={{overflow: 'hidden'}}
                                                direction="column">
                                                <Typography
                                                    color={(theme) => (theme.palette.text.primary)}
                                                    noWrap>
                                                    {item.name}
                                                </Typography>
                                                <Typography
                                                    color={(theme) => (theme.palette.text.secondary)}
                                                    noWrap>
                                                        {(Boolean(item.link) ? (
                                                            <Link
                                                                href={item.link}
                                                                target="_blank">
                                                                {item.card}
                                                            </Link>
                                                        ) : (
                                                            item.card
                                                        ))}
                                                </Typography>
                                            </Stack>
                                        </Stack>
                                    )
                                },
                                {
                                    item: 'rank',
                                    edge: 'center',
                                    name: 'Estado',
                                    show: true,
                                    sort: true,
                                    size: 210,
                                    pick: [
                                        {
                                            item: 0,
                                            tint: '9E9E9E',
                                            text: 'N/A'
                                        },
                                        {
                                            item: 1,
                                            tint: 'D84315',
                                            text: 'Negada'
                                        },
                                        {
                                            item: 2,
                                            tint: '1976D2',
                                            text: 'Publicada'
                                        },
                                        {
                                            item: 3,
                                            tint: '2E7D32',
                                            text: 'Registrada'
                                        },
                                        {
                                            item: 4,
                                            tint: 'F9B825',
                                            text: 'Bajo examen de fondo'
                                        },
                                        {
                                            item: 5,
                                            tint: '880E4F',
                                            text: 'Cancelada'
                                        },
                                        {
                                            item: 6,
                                            tint: 'D32F2F',
                                            text: 'Renuncia total'
                                        },
                                        {
                                            item: 7,
                                            tint: '0277BD',
                                            text: 'Concepto de viabilidad'
                                        },
                                        {
                                            item: 8,
                                            tint: 'FDD835',
                                            text: 'Bajo examen de forma'
                                        },
                                        {
                                            item: 9,
                                            tint: 'D84315',
                                            text: 'Con oposición'
                                        },
                                        {
                                            item: 10,
                                            tint: '2E7D32',
                                            text: 'Caducada'
                                        }
                                    ]
                                },
                                {
                                    item: 'made',
                                    name: 'Fecha',
                                    edge: 'right',
                                    show: true,
                                    sort: true,
                                    size: 180,
                                    cast: (item: any) => (moment(item.createdAt).format('DD/MM/YY LT'))
                                }
                            ]}
                            take={{
                                pick: data.take ?? 16,
                                list: [16,  32, 64]
                            }}
                            find={{
                                hint: 'Search',
                                text: data.find
                            }}
                            none={{
                                text: 'Sin registros',
                                note: 'No se encontraron registros disponibles.'
                            }}
                            wait={data.wait}
                            list={data.list}
                            size={data.size}
                            page={data.page}
                            load={(page, take, find, pipe, sort) => {
                                setData((last) => ({...last, page: page ?? last.page, take: take ?? last.take, sort: sort, find: find, pipe: pipe, wait: true}));
                            }} />
                    </Grid>
                </Grid>
                <Dialog
                    open={(data.form.find.open)}
                    scroll="paper"
                    maxWidth="sm"
                    onClose={(event) => {
                        setData((last) => ({...last, form: {...last.form, find: {...last.form.find, open: false}}}));
                    }}
                    fullWidth>
                    <DialogTitle
                        sx={{px: 3, pt: 4, pb: 1}}
                        alignItems="center"
                        justifyContent="center">
                        <Typography
                            color="text.primary"
                            variant="h5"
                            gutterBottom>
                            Reporte
                        </Typography>
                        <Typography
                            color="text.secondary"
                            gutterBottom>
                            Buscar reporte
                        </Typography>
                    </DialogTitle>
                    <DialogContent>
                        <Form
                            data={data.form.find.data}
                            wait={data.form.find.wait}
                            flat={true}
                            view={{
                                data: [
                                    {
                                        type: 'menu',
                                        size: 'full',
                                        name: 'item',
                                        text: 'Gaceta',
                                        bind: 'La opción es requerida.',
                                        list: data.heap.date.list
                                    }
                                ]
                            } as any}
                            quit={{
                                task: (step, exit) => {
                                    setData((last) => ({...last, form: {...last.form, find: {...last.form.find, open: false}}}));
                                }
                            }}
                            save={{
                                text: 'Buscar',
                                task: async (form: any, step?: number, save?: boolean) => {
                                    if (data.item) {
                                        navigate(`/vigilancia/seek/${data.item.hash}/${form.item}`);
                                    } else {
                                        navigate(`/vigilancia/seek/${form.item}`);
                                    }
                                    
                                    /*Store.take(form.item, form.name, (done: boolean, data: any) => {
                                        setData((last) => ({...last, form: {...last.form, find: {...last.form.find, open: false}}}));

                                        if (done) {
                                            setAlert(`The order #${data.Reference} was submitted successfully and is in pending status.`, 'success');
                                        } else {
                                            setAlert(`The order could not submitted successfully.`, 'error');
                                        }
                                    });*/

                                    setData((last) => ({...last, form: {...last.form, find: {...last.form.find, wait: true}}}));
                                }
                            }} />
                    </DialogContent>
                </Dialog>
                <Dialog
                    open={(data.form.bulk.open)}
                    scroll="paper"
                    maxWidth="sm"
                    onClose={(event, reason) => {
                        if (((reason == 'backdropClick') == false)) {
                            setData((last) => ({
                                ...last,
                                form: {
                                    ...last.form,
                                    bulk: {
                                        ...last.form.bulk,
                                        open: false
                                    }
                                }
                            }));
                        }
                    }}
                    fullWidth>
                    <DialogTitle
                        sx={{px: 3, pt: 4, pb: 1}}
                        alignItems="center"
                        justifyContent="center">
                        <Typography
                            color="text.primary"
                            variant="h5"
                            gutterBottom>
                            Importar
                        </Typography>
                        <Typography
                            color="text.secondary"
                            gutterBottom>
                            Importar marcas
                        </Typography>
                    </DialogTitle>
                    <DialogContent>
                        <Grid
                            spacing={1}
                            container>
                            {(Boolean(data.form.bulk.text) && (
                                <Grid
                                    xs={12}
                                    item>
                                    <Alert severity="error">
                                        {data.form.bulk.text}
                                    </Alert>
                                </Grid>
                            ))}
                            <Grid
                                xs={12}
                                item>
                                <Form
                                    data={data.form.bulk.data}
                                    fail={data.form.bulk.list}
                                    wait={data.form.bulk.wait}
                                    lock={data.form.bulk.wait}
                                    flat={true}
                                    view={{
                                        data: [
                                            {
                                                type: 'list',
                                                size: 'full',
                                                name: 'firm',
                                                text: 'Empresa',
                                                hint: 'Seleccione una opción.',
                                                bind: 'El documento es requerido.',
                                                list: (((session.type == 1) ? session.heap?.firm : session.heap?.firm?.filter((item: any) => (item.lead == session.link))) ?? []).map((next: any) => ({item: next.item, text: next.name, hint: next.card}))
                                            },
                                            {
                                                type: 'file',
                                                size: 'full',
                                                name: 'file',
                                                kind: ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
                                                text: 'Documento',
                                                hint: 'El documento no puede pesar más de 6 MB',
                                                bind: 'El documento es requerido.'
                                            }
                                        ]
                                    } as any}
                                    quit={{
                                        task: (step, exit) => {
                                            setData((last) => ({...last, form: {...last.form, bulk: {...last.form.bulk, open: false}}}));
                                        }
                                    }}
                                    save={{
                                        text: 'Importar',
                                        task: (form: any, step?: number, save?: boolean) => {
                                            Store.bulk(form, (done: boolean, data: any) => {
                                                if (done) {
                                                    setData((last) => ({
                                                        ...last,
                                                        form: {
                                                            ...last.form,
                                                            bulk: {
                                                                ...last.form.bulk,
                                                                open: false,
                                                                wait: false
                                                            }
                                                        }
                                                    }));

                                                    setAlert((data?.text ?? 'Las solicitudes fueron importadas correctamente.'), 'success');
                                                } else {
                                                    setData((last) => ({
                                                        ...last,
                                                        form: {
                                                            ...last.form,
                                                            bulk: {
                                                                ...last.form.bulk,
                                                                wait: false,
                                                                text: data?.text ?? 'El comentario no pudo ser guardado correctamente.',
                                                                list: data?.list ?? {}
                                                            }
                                                        }
                                                    }));
                                                }
                                            });

                                            setData((last) => ({
                                                ...last,
                                                form: {
                                                    ...last.form,
                                                    bulk: {
                                                        ...last.form.bulk,
                                                        wait: true,
                                                        text: null,
                                                        list: null
                                                    }
                                                }
                                            }));
                                        }
                                    }} />
                            </Grid>
                        </Grid>
                    </DialogContent>
                </Dialog>
                <Dialog
                    open={(data.form.note.open)}
                    onClose={(event, reason) => {
                        if ((((reason == 'backdropClick') && data.form.note.wait) == false)) {
                            setData((last) => ({
                                ...last,
                                form: {
                                    ...last.form,
                                    note: {
                                        ...last.form.note,
                                        open: false
                                    }
                                }
                            }));
                        }
                    }}
                    maxWidth="sm"
                    fullWidth>
                    <DialogTitle
                        sx={{px: 3, pt: 4, pb: 1}}
                        alignItems="center"
                        justifyContent="center">
                        <Typography
                            color="text.primary"
                            variant="h5"
                            gutterBottom>
                            Comentario
                        </Typography>
                        <Typography
                            color="text.secondary"
                            gutterBottom>
                            Agregar comentario.
                        </Typography>
                    </DialogTitle>
                    <DialogContent>
                        <Grid
                            spacing={1}
                            container>
                            {(Boolean(data.form.note.text) && (
                                <Grid
                                    xs={12}
                                    item>
                                    <Alert severity="error">
                                        {data.form.note.text}
                                    </Alert>
                                </Grid>
                            ))}
                            <Grid
                                xs={12}
                                item>
                                <Form
                                    data={data.form.note.data}
                                    fail={data.form.note.list}
                                    wait={data.form.note.wait}
                                    lock={data.form.note.wait}
                                    flat={true}
                                    view={{
                                        data: [
                                            {
                                                type: 'menu',
                                                size: 'full',
                                                name: 'rank',
                                                text: 'Prioridad',
                                                hint: 'Seleccione una opción',
                                                bind: 'La opción es requerida.',
                                                list: [
                                                    {item: 1, text: 'Baja'},
                                                    {item: 2, text: 'Media'},
                                                    {item: 3, text: 'Alta'}
                                                ]
                                            },
                                            {
                                                type: 'area',
                                                size: 'full',
                                                name: 'text',
                                                text: 'Comentario',
                                                hint: 'Comentario acerca del reporte',
                                                bind: 'El campo es requerido.'
                                            },
                                            {
                                                type: 'file',
                                                size: 'full',
                                                name: 'file',
                                                text: 'Documento',
                                                hint: 'El documento no puede pesar más de 6 MB'
                                            },
                                            {
                                                type: 'turn',
                                                size: 'full',
                                                name: 'mail',
                                                text: 'Enviar correo electrónico'
                                            },
                                            {
                                                type: 'text',
                                                size: 'full',
                                                name: 'case',
                                                text: 'Asunto',
                                                hint: 'Asunto del correo electrónico',
                                                hide: (form: Hash<any>) => (((form.mail ?? false) == false))
                                            }
                                        ]
                                    } as any}
                                    quit={{
                                        task: (step, exit) => {
                                            setData((last) => ({...last, form: {...last.form, note: {...last.form.note, open: false}}}));
                                        }
                                    }}
                                    save={{
                                        text: 'Guardar',
                                        task: (form: any, step?: number, save?: boolean) => {
                                            Store.make(form, (done: boolean, data: any) => {
                                                if (done) {
                                                    setData((last) => ({
                                                        ...last,
                                                        form: {
                                                            ...last.form,
                                                            note: {
                                                                ...last.form.note,
                                                                open: false,
                                                                wait: false
                                                            }
                                                        }
                                                    }));

                                                    setAlert((data?.text ?? 'El comentario fue guardado correctamente.'), 'success');
                                                } else {
                                                    setData((last) => ({
                                                        ...last,
                                                        form: {
                                                            ...last.form,
                                                            note: {
                                                                ...last.form.note,
                                                                wait: false,
                                                                text: data?.text ?? 'El comentario no pudo ser guardado correctamente.',
                                                                list: data?.list ?? {}
                                                            }
                                                        }
                                                    }));
                                                }
                                            }, data.form.note.item.hash, 'note');

                                            setData((last) => ({
                                                ...last,
                                                form: {
                                                    ...last.form,
                                                    note: {
                                                        ...last.form.note,
                                                        wait: true,
                                                        text: null,
                                                        list: null
                                                    }
                                                }
                                            }));
                                        }
                                    }} />
                            </Grid>
                        </Grid>
                    </DialogContent>
                </Dialog>
                <Dialog
                    open={(data.form.risk.open)}
                    scroll="paper"
                    maxWidth="xl"
                    onClose={(event) => {
                        setData((last) => ({
                            ...last,
                            form: {
                                ...last.form,
                                risk: {
                                    ...last.form.risk,
                                    open: false
                                }
                            }
                        }));
                    }}
                    fullWidth>
                    <DialogTitle
                        sx={{px: 3, pt: 4, pb: 1}}
                        alignItems="center"
                        justifyContent="center">
                        <Typography
                            color="text.primary"
                            variant="h5"
                            gutterBottom>
                            Viabilidad
                        </Typography>
                        <Typography
                            color="text.secondary"
                            gutterBottom>
                            Concepto de viabilidad.
                        </Typography>
                    </DialogTitle>
                    <DialogContent ref={node}>
                        {(data.form.risk.wait ? (
                            <Box
                                justifyContent="center"
                                alignItems="center"
                                display="flex"
                                height={96}>
                                <CircularProgress size={48} />
                            </Box>
                        ) : (data.form.risk.done ? (
                            <Box
                                sx={{'@media print': {
                                    '@page': {
                                        size: '16in 12in',
                                        margin: '2mm'
                                    }
                                    }}}
                                ref={node}
                                dangerouslySetInnerHTML={{__html: (data.form.risk.data ?? '')}} />
                        ) : (
                            <Alert severity="error">
                                {(data.form.risk.fail ?? 'No se pudo descargar el informe.')}
                            </Alert>
                        )))}
                    </DialogContent>
                    <DialogActions sx={{pt: 3, px: 3, pb: 2}}>
                        <Grid
                            spacing={2}
                            container>
                            <Grid
                                xs={12}
                                item>
                                <Stack
                                    justifyContent="end"
                                    direction="row"
                                    spacing={2}>
                                    <Button
                                        type="reset"
                                        color="secondary"
                                        variant="contained"
                                        disabled={data.form.risk.wait}
                                        onClick={(event) => {
                                            setData((last) => ({
                                                ...last,
                                                form: {
                                                    ...last.form,
                                                    risk: {
                                                        ...last.form.risk,
                                                        open: false
                                                    }
                                                }
                                            }));
                                        }}>
                                        Cancelar
                                    </Button>
                                    <Button
                                        type="reset"
                                        color="primary"
                                        variant="contained"
                                        disabled={(data.form.risk.wait || (data.form.risk.done == false))}
                                        onClick={(event) => {
                                            dump();
                                        }}>
                                        Imprimir
                                    </Button>
                                </Stack>
                            </Grid>
                        </Grid>
                    </DialogActions>
                </Dialog>
            </Fragment>
        );
    }

    export const Make = () => {
        const navigate = useNavigate();

        const {session} = useSession();

        const [form, setForm] = useState<{
            wait: boolean,
            lock: boolean,
            fail: Hash<string>,
            load: Hash<any>,
            data: Hash<any>
        }>({
            load: {
                Kind: {
                    wait: false,
                    fail: null,
                    find: null,
                    list: null
                }
            },
            data: {
                sort: [],
                data: [],
                risk: [],
                rate: [],
                seek: [],
                type: null,
                bind: null,
                rank: null,
                link: null,
                name: null,
                notice: 'Importante: El alcance de un concepto sobre registrabilidad marcaria es solamente el de una opinión, fundamentada en nuestra experiencia y en la revisión detallada de múltiples aspectos, pero no corresponde a una garantía de registrabilidad.\n\nLa determinación definitiva es realizada autónoma y oficiosamente por la Superintendencia de Industria y Comercio.\n\nLa presente búsqueda fue realizada con la información disponible en la página de la SIC a fecha 30 deseptiembre de 2024.',
                proxy: null,
                number: null,
                titular: null,
                schedule: null,
                reference: null,
                opposition: null,
                concession: null,
                validation: null,
                information: null,
                publication: null,
                introduction: null,
                presentation: null
            },
            wait: false,
            lock: false,
            fail: {name: 'Invalid'}
        });

        const view: any = {
            data: [
                {
                    type: 'file',
                    snap: true,
                    path: (icon: any) => (`${BACK}/snaps/${icon}`),
                    name: 'icon',
                    text: 'Subir nueva imágen'
                },
                {
                    step: 0,
                    line: true,
                    name: 'Main',
                    text: 'Básica',
                    hint: 'Información básica'
                },
                {
                    type: 'menu',
                    size: 'half',
                    name: 'type',
                    text: 'Tipo',
                    hint: 'Seleccione una opción',
                    bind: 'La opción es requerida.',
                    list: [
                        {item: 1, text: 'Mixta'},
                        {item: 2, text: 'Nominativa'},
                        {item: 3, text: 'No Figurativa'},
                        {item: 4, text: '3D'},
                        {item: 5, text: 'Sonido'},
                        {item: 6, text: 'Tridimensional mixta'},
                        {item: 7, text: 'LEMA COMERCIAL'}
                    ]
                },
                {
                    type: 'list',
                    size: 'half',
                    name: 'bind',
                    text: 'Empresa',
                    list: session.heap?.firm?.map((next: any) => ({item: next.item, text: next.name, hint: next.card})) ?? []
                },
                {
                    type: 'text',
                    size: 'half',
                    name: 'name',
                    text: 'Nombre',
                    hint: 'Nombre de la marca',
                    bind: 'El campo es requerido.'
                },
                {
                    type: 'text',
                    size: 'tiny',
                    name: 'number',
                    text: 'Número del caso',
                    hint: 'Número del caso'
                },
                {
                    type: 'menu',
                    size: 'tiny',
                    name: 'rank',
                    text: 'Estado del caso',
                    hint: 'Seleccione una opción.',
                    bind: 'El campo es requerido.',
                    list: [
                        {item: 1, text: 'Negada'},
                        {item: 2, text: 'Publicada'},
                        {item: 3, text: 'Registrada'},
                        {item: 4, text: 'Bajo examen de fondo'},
                        {item: 5, text: 'Cancelada'},
                        {item: 6, text: 'Renuncia total'},
                        {item: 7, text: 'Concepto de viabilidad'},
                        {item: 8, text: 'Bajo examen de forma'},
                        {item: 9, text: 'Con oposición'},
                        {item: 10, text: 'Caducada'}
                    ]
                },
                {
                    type: 'text',
                    size: 'half',
                    name: 'title',
                    text: 'Título del caso',
                    hint: 'Título del caso'
                },
                {
                    type: 'text',
                    size: 'half',
                    name: 'link',
                    text: 'Enlace del caso',
                    hint: 'Enlace del caso'
                },
                {
                    type: 'text',
                    size: 'half',
                    name: 'titular',
                    text: 'Titular',
                    hint: 'Nombre del titular'
                },
                {
                    type: 'text',
                    size: 'half',
                    name: 'reference',
                    text: 'Referencia del solicitante',
                    hint: 'Referencia del solicitante'
                },
                {
                    type: 'date',
                    size: 'half',
                    name: 'validation',
                    text: 'Vigencia'
                },
                {
                    type: 'date',
                    size: 'half',
                    name: 'publication',
                    text: 'Fecha de publicación'
                },
                {
                    type: 'date',
                    size: 'half',
                    name: 'presentation',
                    text: 'Fecha de radicación'
                },
                {
                    type: 'date',
                    size: 'half',
                    name: 'registration',
                    text: 'Fecha de concesión'
                },
                {
                    span: true,
                    type: 'list',
                    size: 'full',
                    name: 'kind',
                    text: 'Clases',
                    list: form.load.Kind.list?.map((next: any) => ({item: next.code, text: next.code, note: next.note, more: next.more, hint: (next.code == next.name) ? null : next.name})) ?? []
                },
                {
                    type: 'text',
                    size: 'full',
                    name: 'proxy',
                    text: 'Apoderado',
                    hint: 'Nombre del apoderado'
                },
                {
                    type: 'area',
                    size: 'full',
                    name: 'notice',
                    text: 'Aviso importante'
                },
                {
                    type: 'area',
                    size: 'full',
                    name: 'information',
                    text: 'Otra información'
                },
                {
                    line: true,
                    name: 'Sort',
                    text: 'Conceptos',
                    hint: 'Clases para el concepto de viabilidad.'
                },
                {
                    tile: true,
                    type: 'text',
                    size: 'full',
                    name: 'sort',
                    text: 'Agergar concepto',
                    form: {
                        size: 0,
                        high: 16,
                        list: [
                            {
                                type: 'list',
                                size: 'full',
                                name: 'code',
                                text: 'Clase',
                                bind: 'El campo es requerido.',
                                list: form.load.Kind.list?.map((next: any) => ({item: next.code, text: next.code, note: next.note, more: next.more, hint: (next.code == next.name) ? null : next.name})) ?? [],
                                live: (data: any, form: any, item: any) => {
                                    form.sort[item] = {
                                        ...form.sort[item],
                                        note: data.note,
                                        more: data.more
                                    };

                                    setForm((last) => ({
                                        ...last,
                                        data: {
                                            ...last.data,
                                            ...form
                                        }
                                    }));
                                }
                            },
                            {
                                type: 'area',
                                size: 'full',
                                name: 'note',
                                text: 'Descripción',
                                bind: 'El campo es requerido.'
                            },
                            {
                                type: 'area',
                                size: 'full',
                                name: 'more',
                                text: 'Incluye'
                            }
                        ],
                        push: (next: number) => ({code: null, note: null, more: null})
                    }
                },
                {
                    line: true,
                    name: 'Data',
                    text: 'Catálogo',
                    hint: 'Descripción productos y servicios.'
                },
                {
                    tile: true,
                    type: 'text',
                    size: 'full',
                    name: 'data',
                    text: 'Agergar descripción',
                    form: {
                        size: 0,
                        high: 16,
                        list: [
                            {
                                type: 'text',
                                name: 'code',
                                text: 'Código',
                                size: 'tiny',
                                hint: 'Código del producto o servicio',
                                bind: 'El código es requerido.'
                            },
                            {
                                type: 'text',
                                name: 'text',
                                text: 'Descripción',
                                size: 'wide',
                                hint: 'Descripción del producto o servicio',
                                bind: 'La Descripción es requerida.'
                            }
                        ],
                        push: (next: number) => ({code: null, text: null})
                    }
                },
                {
                    line: true,
                    name: 'Data',
                    text: 'Oposición',
                    hint: 'Marcas similares con riesgo de oposición.'
                },
                {
                    type: 'area',
                    size: 'full',
                    name: 'introduction',
                    text: 'Introducción'
                },
                {
                    tile: true,
                    type: 'text',
                    size: 'full',
                    name: 'risk',
                    text: 'Agergar oposición',
                    form: {
                        size: 0,
                        high: 16,
                        list: [
                            {
                                type: 'file',
                                snap: true,
                                path: (icon: any) => (`${BACK}/snaps/${icon}`),
                                name: 'icon',
                                text: 'Subir nueva imágen'
                            },
                            {
                                type: 'menu',
                                size: 'tiny',
                                name: 'type',
                                text: 'Tipo',
                                hint: 'Seleccione una opción.',
                                bind: 'El campo es requerido.',
                                list: [
                                    {item: 1, text: 'Mixta'},
                                    {item: 2, text: 'Nominativa'},
                                    {item: 3, text: 'No Figurativa'},
                                    {item: 4, text: '3D'},
                                    {item: 5, text: 'Sonido'},
                                    {item: 6, text: 'Tridimensional mixta'},
                                    {item: 7, text: 'LEMA COMERCIAL'}
                                ]
                            },
                            {
                                type: 'text',
                                name: 'code',
                                text: 'Expediente',
                                size: 'tiny',
                                bind: 'El campo es requerido.'
                            },
                            {
                                type: 'text',
                                name: 'name',
                                text: 'Nombre',
                                size: 'half',
                                bind: 'El campo es requerido.'
                            },
                            {
                                type: 'text',
                                name: 'skip',
                                text: 'Titular',
                                size: 'half',
                                bind: 'El campo es requerido.'
                            },
                            {
                                type: 'date',
                                size: 'half',
                                name: 'date',
                                text: 'Vigencia',
                                bind: 'El campo es requerido.'
                            },
                            {
                                span: true,
                                type: 'list',
                                size: 'full',
                                name: 'sort',
                                text: 'Clases',
                                list: form.load.Kind.list?.map((next: any) => ({item: next.code, text: next.code, note: next.note, more: next.more, hint: (next.code == next.name) ? null : next.name})) ?? []
                            },
                            {
                                type: 'area',
                                name: 'note',
                                text: 'Análisis de Riesgo',
                                size: 'full',
                                bind: 'El campo es requerido.'
                            },
                            {
                                type: 'area',
                                name: 'zone',
                                text: 'Cobertura',
                                size: 'full'
                            }
                        ],
                        push: (next: number) => ({code: null, name: null, skip: null, date: null, type: null, note: null, zone: null, icon: null, sort: []})
                    }
                },
                {
                    line: true,
                    name: 'Data',
                    text: 'Viabilidad',
                    hint: 'Viabilidad de registro estimada.'
                },
                {
                    tile: true,
                    type: 'text',
                    size: 'full',
                    name: 'rate',
                    text: 'Agergar viabilidad',
                    form: {
                        size: 0,
                        high: 16,
                        list: [
                            {
                                span: true,
                                type: 'list',
                                size: 'full',
                                name: 'sort',
                                text: 'Clases',
                                bind: 'El campo es requerido.',
                                list: form.load.Kind.list?.map((next: any) => ({item: next.code, text: next.code, note: next.note, more: next.more, hint: (next.code == next.name) ? null : next.name})) ?? []
                            },
                            {
                                type: 'text',
                                name: 'load',
                                text: 'Porcentaje',
                                size: 'tiny',
                                bind: 'El campo es requerido.'
                            },
                            {
                                type: 'text',
                                name: 'note',
                                text: 'Resumen',
                                size: 'wide',
                                bind: 'El campo es requerido.'
                            }
                        ],
                        push: (next: number) => ({load: null, note: null, sort: []})
                    }
                },
                {
                    line: true,
                    name: 'Data',
                    text: 'Resultados',
                    hint: 'Resultados de la búsqueda en SIC.'
                },
                {
                    type: 'file',
                    kind: ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.google-apps.spreadsheet', 'application/vnd.ms-excel'],
                    name: 'bulk',
                    hint: 'Seleccione un documento para importar.',
                    live: (pick: Null<File>) => {
                        if (pick) {
                            const book = new Excel.Workbook();

                            const file = new FileReader();

                            const head: Array<Hash<any>> = [
                                {slot: null, name: 'Expediente'},
                                {slot: null, name: 'Denominacion'},
                                {slot: null, name: 'Vigencia'},
                                {slot: null, name: 'Estado'},
                                {slot: null, name: 'Titular'},
                                {slot: null, name: 'Clases'}
                            ];

                            let push = false;

                            let list: Array<Hash<any>> = [];

                            file.readAsArrayBuffer(pick);

                            file.onload = (event) => {
                                if (event?.target?.result) {
                                    book.xlsx.load(event.target.result as ArrayBuffer).then(work => {
                                        work.eachSheet((sheet) => {
                                            sheet.eachRow((data) => {
                                                if ((data.values instanceof Array)) {
                                                    if (push) {
                                                        list.push({
                                                            code: data.values[head[0].slot] ?? null,
                                                            name: data.values[head[1].slot] ?? null,
                                                            skip: data.values[head[4].slot] ?? null,
                                                            date: moment(data.values[head[2].slot]?.toString()).format('YYYY-MM-DD'),
                                                            rank: {
                                                                'negada': 1,
                                                                'publicada': 2,
                                                                'concedida': 3,
                                                                'registrada': 3,
                                                                'bajo examen de fondo': 4,
                                                                'vancelada': 5,
                                                                'renuncia total': 6,
                                                                'concepto de viabilidad': 7,
                                                                'bajo examen de forma': 8,
                                                                'con oposición': 9,
                                                                'caducada': 10
                                                            }[(data.values[head[3].slot]?.toString()?.toLocaleLowerCase() ?? '')] ?? null,
                                                            sort: data.values[head[5].slot]?.toString()?.split(',')?.filter((item: string) => (Boolean(item.trim()))) ?? []
                                                        });
                                                    } else {
                                                        data.values.forEach((data, next) => {
                                                            let item = head.find((item: any) => (item.name == data));

                                                            if (item) {
                                                                item.slot = next;
                                                            }
                                                        });

                                                        push = head.every((item: any) => (Boolean(item.slot)));
                                                    }
                                                }
                                            })
                                        });

                                        setForm((last) => ({
                                            ...last,
                                            data: {
                                                ...last.data,
                                                seek: list,
                                                bulk: null
                                            }
                                        }));
                                    })
                                }
                            }
                        }
                    }
                },
                {
                    tile: true,
                    type: 'text',
                    size: 'full',
                    name: 'seek',
                    text: 'Agergar resultado',
                    form: {
                        size: 0,
                        high: 16,
                        list: [
                            {
                                type: 'menu',
                                size: 'tiny',
                                name: 'rank',
                                text: 'Estado',
                                hint: 'Seleccione una opción.',
                                bind: 'El campo es requerido.',
                                list: [
                                    {item: 1, text: 'Negada'},
                                    {item: 2, text: 'Publicada'},
                                    {item: 3, text: 'Registrada'},
                                    {item: 4, text: 'Bajo examen de fondo'},
                                    {item: 5, text: 'Cancelada'},
                                    {item: 6, text: 'Renuncia total'},
                                    {item: 7, text: 'Concepto de viabilidad'},
                                    {item: 8, text: 'Bajo examen de forma'},
                                    {item: 9, text: 'Con oposición'},
                                    {item: 10, text: 'Caducada'}
                                ]
                            },
                            {
                                type: 'text',
                                name: 'code',
                                text: 'Expediente',
                                size: 'tiny',
                                bind: 'El campo es requerido.'
                            },
                            {
                                type: 'text',
                                name: 'name',
                                text: 'Nombre',
                                size: 'half',
                                bind: 'El campo es requerido.'
                            },
                            {
                                type: 'text',
                                name: 'skip',
                                text: 'Titular',
                                size: 'half',
                                bind: 'El campo es requerido.'
                            },
                            {
                                type: 'date',
                                size: 'half',
                                name: 'date',
                                text: 'Vigencia',
                                bind: 'El campo es requerido.'
                            },
                            {
                                span: true,
                                type: 'list',
                                size: 'full',
                                name: 'sort',
                                text: 'Clases',
                                list: form.load.Kind.list?.map((next: any) => ({item: next.code, text: next.code, note: next.note, more: next.more, hint: (next.code == next.name) ? null : next.name})) ?? []
                            }
                        ],
                        push: (next: number) => ({code: null, name: null, skip: null, date: null, rank: null, sort: []})
                    }
                }
            ]
        };

        const {setAlert, setTitle} = useApplication();

        useEffect(() => {
            setTitle('Agregar Marca');

            Kinds.load(0, null, null, null, null, null, (done, data) => {
                if (done) {
                    setForm((last) => ({
                        ...last,
                        load: {
                            ...last.load,
                            Kind: {
                                ...last.load.Kind,
                                list: data.data,
                                wait: false
                            }
                        }
                    }));
                } else {
                    setForm((last) => ({
                        ...last,
                        load: {
                            ...last.load,
                            Kind: {
                                ...last.load.Kind,
                                wait: false,
                                fail: true
                            }
                        }
                    }));
                }
            });
        }, []);

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
                        Agregar Marca
                    </Typography>
                    <Typography
                        color="text.secondary"
                        gutterBottom>
                        Módulo de vigilancia de marcas.
                    </Typography>
                </Grid>
                <Grid
                    display="flex"
                    flex={1}
                    item>
                    <Form
                        view={view}
                        data={form.data}
                        fail={form.fail}
                        wait={form.wait}
                        lock={form.lock}
                        quit={{
                            text: 'Cancelar',
                            task: () => {
                                navigate('/vigilancia');
                            }
                        }}
                        save={{
                            text: 'Guardar',
                            task: async (form: Hash<any>) => {
                                setForm((last) => ({
                                    ...last,
                                    lock: true,
                                    fail: {}
                                }));
                                
                                Store.make(form, (done: boolean, data: any) => {
                                    if (done) {
                                        setAlert((data?.text ?? 'El registro fue creado correctamente.'), 'success');

                                        setForm((last) => ({
                                            ...last,
                                            lock: false,
                                            data: {
                                                ...last.data,
                                                ...form,
                                                ...{
                                                    sort: [],
                                                    data: [],
                                                    risk: [],
                                                    rate: [],
                                                    seek: [],
                                                    type: null,
                                                    bind: null,
                                                    rank: null,
                                                    link: null,
                                                    name: null,
                                                    proxy: null,
                                                    number: null,
                                                    titular: null,
                                                    schedule: null,
                                                    reference: null,
                                                    opposition: null,
                                                    concession: null,
                                                    validation: null,
                                                    information: null,
                                                    publication: null,
                                                    introduction: null,
                                                    presentation: null
                                                }
                                            }
                                        }));
                                    } else {
                                        setAlert((data?.text ?? 'El registro no pudo ser creado correctamente.'), 'error');

                                        setForm((last) => ({
                                            ...last,
                                            lock: false,
                                            fail: data.form,
                                            data: {
                                                ...last.data,
                                                ...form
                                            }
                                        }));
                                    }
                                });
                            }
                        }}
                    />
                </Grid>
            </Grid>
        );
    }

    export const View = () => {
        const {
            item,
            type
        } = useParams();
    
        const {
            setAlert,
            setTitle,
            setDialog
        } = useApplication();

        const {session} = useSession();

        const [done, setDone] =  useState(false);

        const [fail, setFail] =  useState(false);

        const [data, setData] = useState<{
            pipe: Hash<{data: Null<any>, sign: '~' | '=' | '<' | '>' | '!'}>,
            list: Array<Hash<any>>,
            item: Null<Hash<any>>,
            sort: Hash<any>,
            form: Hash<any>,
            wait: boolean,
            lock: boolean,
            take: number,
            page: number,
            size: number,
            time: number,
            find: string
        }>({
            form: {
                note: {
                    wait: false,
                    open: false,
                    item: null,
                    text: null,
                    part: null,
                    data: {
                        term: null,
                        rate: null,
                        rank: null,
                        text: null,
                        file: null
                    }
                }
            },
            wait: false,
            lock: false,
            item: null,
            list: [],
            pipe: {},
            sort: {},
            take: 16,
            page: 1,
            size: 0,
            time: 0,
            find: ''
        });

        useEffect(() => {
            Store.find(`${item}`, '', (done, data) => {
                setTimeout(() => {
                    if (done) {
                        setDone(true);

                        setData((last) => ({
                            ...last,
                            item: data.item,
                            list: data.list
                        }));

                        setDone(true);
                    } else {
                        setFail(true);
                    }
                }, 300);
            });
        }, [item]);

        return (
            <Fragment>
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
                            Detalles
                        </Typography>
                        <Typography
                            color="text.secondary"
                            gutterBottom>
                            Módulo de vigilancia de marcas
                        </Typography>
                    </Grid>
                    <Grid
                        display="flex"
                        flex={1}
                        item>
                        {(done ? (
                            <Grid
                                alignContent="start"
                                spacing={2}
                                container>
                                <Grid
                                    xs={12}
                                    item>
                                    <Card variant="elevation">
                                        <Stack>
                                            <Typography
                                                variant="h2"
                                                color="#FFFFFF"
                                                align="center"
                                                sx={{background: '#232523', px: 4, pt: 2, pb: 2}}>
                                                {data.item?.name}
                                            </Typography>
                                        </Stack>
                                        <Stack sx={{background: '#B7BBDB', padding: 4}}>
                                            <Grid
                                                spacing={2}
                                                container>
                                                <Grid
                                                    xs={3}
                                                    item>
                                                    <Avatar
                                                        variant="square"
                                                        src={(data.item?.icon && `${BACK}/snaps/${data.item?.icon}/thumb`)}
                                                        sx={(theme) => ({
                                                            width: 250,
                                                            height: 250,
                                                            background: '#FFFFFF'
                                                        })}>
                                                        {data.item?.name[0]}
                                                    </Avatar>
                                                </Grid>
                                                <Grid
                                                    xs={9}
                                                    item>
                                                    <Grid
                                                        spacing={2}
                                                        container>
                                                        <Grid
                                                            xs={12}
                                                            item>
                                                            <Typography color="#232523">
                                                                {data.item?.information}
                                                            </Typography>
                                                        </Grid>
                                                        <Grid
                                                            xs={12}
                                                            item>
                                                            <Grid
                                                                spacing={2}
                                                                container>
                                                                <Grid
                                                                    xs={6}
                                                                    item>
                                                                    <Card
                                                                        variant="elevation"
                                                                        sx={{padding: 2}}>
                                                                        <Stack>
                                                                            <Typography color="text.secondary">
                                                                                Tipo
                                                                            </Typography>
                                                                            <Typography color="text.primary">
                                                                                {{1: 'Mixta',
                                                                                  2: 'Nominativa',
                                                                                  3: 'No Figurativa',
                                                                                  4: '3D',
                                                                                  5: 'Sonido',
                                                                                  6: 'Tridimensional mixta',
                                                                                  7: 'LEMA COMERCIAL'}[data.item?.type as number]}
                                                                            </Typography>
                                                                        </Stack>
                                                                    </Card>
                                                                </Grid>
                                                                <Grid
                                                                    xs={6}
                                                                    item>
                                                                    <Card
                                                                        variant="elevation"
                                                                        sx={{padding: 2}}>
                                                                        <Stack>
                                                                            <Typography color="text.secondary">
                                                                                Nombre
                                                                            </Typography>
                                                                            <Typography color="text.primary">
                                                                                {data.item?.name}
                                                                            </Typography>
                                                                        </Stack>
                                                                    </Card>
                                                                </Grid>
                                                                <Grid
                                                                    xs={6}
                                                                    item>
                                                                    <Card
                                                                        variant="elevation"
                                                                        sx={{padding: 2}}>
                                                                        <Stack>
                                                                            <Typography color="text.secondary">
                                                                                Número del caso
                                                                            </Typography>
                                                                            <Typography color="text.primary">
                                                                                <Link
                                                                                    href={data.item?.link}
                                                                                    target="_blank">
                                                                                    {data.item?.number}
                                                                                </Link>
                                                                            </Typography>
                                                                        </Stack>
                                                                    </Card>
                                                                </Grid>
                                                                <Grid
                                                                    xs={6}
                                                                    item>
                                                                    <Card
                                                                        variant="elevation"
                                                                        sx={{padding: 2}}>
                                                                        <Stack>
                                                                            <Typography color="text.secondary">
                                                                                Estado del caso
                                                                            </Typography>
                                                                            <Typography color="text.primary">
                                                                                {{1: 'Negada',
                                                                                  2: 'Publicada',
                                                                                  3: 'Registrada',
                                                                                  4: 'Bajo examen de fondo',
                                                                                  5: 'Cancelada',
                                                                                  6: 'Renuncia total',
                                                                                  7: 'Concepto de viabilidad',
                                                                                  8: 'Bajo examen de forma',
                                                                                  9: 'Con oposición',
                                                                                  10: 'Caducada'}[data.item?.rank as number]}
                                                                            </Typography>
                                                                        </Stack>
                                                                    </Card>
                                                                </Grid>
                                                                <Grid
                                                                    xs={6}
                                                                    item>
                                                                    <Card
                                                                        variant="elevation"
                                                                        sx={{padding: 2}}>
                                                                        <Stack>
                                                                            <Typography color="text.secondary">
                                                                                Título del caso
                                                                            </Typography>
                                                                            <Typography color="text.primary">
                                                                                {data.item?.title}
                                                                            </Typography>
                                                                        </Stack>
                                                                    </Card>
                                                                </Grid>
                                                                <Grid
                                                                    xs={6}
                                                                    item>
                                                                    <Card
                                                                        variant="elevation"
                                                                        sx={{padding: 2}}>
                                                                        <Stack>
                                                                            <Typography color="text.secondary">
                                                                                Titular
                                                                            </Typography>
                                                                            <Typography color="text.primary">
                                                                                {data.item?.titular}
                                                                            </Typography>
                                                                        </Stack>
                                                                    </Card>
                                                                </Grid>
                                                                <Grid
                                                                    xs={6}
                                                                    item>
                                                                    <Card
                                                                        variant="elevation"
                                                                        sx={{padding: 2}}>
                                                                        <Stack>
                                                                            <Typography color="text.secondary">
                                                                                Referencia del solicitante
                                                                            </Typography>
                                                                            <Typography color="text.primary">
                                                                                {data.item?.reference}
                                                                            </Typography>
                                                                        </Stack>
                                                                    </Card>
                                                                </Grid>
                                                                <Grid
                                                                    xs={6}
                                                                    item>
                                                                    <Card
                                                                        variant="elevation"
                                                                        sx={{padding: 2}}>
                                                                        <Stack>
                                                                            <Typography color="text.secondary">
                                                                                Vigencia
                                                                            </Typography>
                                                                            <Typography color="text.primary">
                                                                                {(data.item?.validation && moment(data.item?.validation).format('DD/MM/YYYY'))}
                                                                            </Typography>
                                                                        </Stack>
                                                                    </Card>
                                                                </Grid>
                                                                <Grid
                                                                    xs={6}
                                                                    item>
                                                                    <Card
                                                                        variant="elevation"
                                                                        sx={{padding: 2}}>
                                                                        <Stack>
                                                                            <Typography color="text.secondary">
                                                                                Fecha de publicación
                                                                            </Typography>
                                                                            <Typography color="text.primary">
                                                                                {(data.item?.publication && moment(data.item?.publication).format('DD/MM/YYYY'))}
                                                                            </Typography>
                                                                        </Stack>
                                                                    </Card>
                                                                </Grid>
                                                                <Grid
                                                                    xs={6}
                                                                    item>
                                                                    <Card
                                                                        variant="elevation"
                                                                        sx={{padding: 2}}>
                                                                        <Stack>
                                                                            <Typography color="text.secondary">
                                                                                Fecha de radicación
                                                                            </Typography>
                                                                            <Typography color="text.primary">
                                                                                {(data.item?.presentation && moment(data.item?.presentation).format('DD/MM/YYYY'))}
                                                                            </Typography>
                                                                        </Stack>
                                                                    </Card>
                                                                </Grid>
                                                                <Grid
                                                                    xs={6}
                                                                    item>
                                                                    <Card
                                                                        variant="elevation"
                                                                        sx={{padding: 2}}>
                                                                        <Stack>
                                                                            <Typography color="text.secondary">
                                                                                Fecha de concesión
                                                                            </Typography>
                                                                            <Typography color="text.primary">
                                                                                {(data.item?.registration && moment(data.item?.registration).format('DD/MM/YYYY'))}
                                                                            </Typography>
                                                                        </Stack>
                                                                    </Card>
                                                                </Grid>
                                                                <Grid
                                                                    xs={6}
                                                                    item>
                                                                    <Card
                                                                        variant="elevation"
                                                                        sx={{padding: 2}}>
                                                                        <Stack>
                                                                            <Typography color="text.secondary">
                                                                                Apoderado
                                                                            </Typography>
                                                                            <Typography color="text.primary">
                                                                                {data.item?.proxy}
                                                                            </Typography>
                                                                        </Stack>
                                                                    </Card>
                                                                </Grid>
                                                                <Grid
                                                                    xs={12}
                                                                    item>
                                                                    <Card
                                                                        variant="elevation"
                                                                        sx={{padding: 2}}>
                                                                        <Stack>
                                                                            <Typography color="text.secondary">
                                                                                Clases
                                                                            </Typography>
                                                                            <Typography color="text.primary">
                                                                                {data.item?.sort?.map((item: any) => (item.code))?.join(', ')}
                                                                            </Typography>
                                                                        </Stack>
                                                                    </Card>
                                                                </Grid>
                                                                <Grid
                                                                    xs={12}
                                                                    item>
                                                                    <Card
                                                                        variant="elevation"
                                                                        sx={{padding: 2}}>
                                                                        <Stack>
                                                                            <Typography color="text.secondary">
                                                                                Catálogo
                                                                            </Typography>
                                                                            <Typography color="text.primary">
                                                                                {data.item?.data?.map((item: any) => (`${item.code}: ${item.text}`)).join(', ')}
                                                                            </Typography>
                                                                        </Stack>
                                                                    </Card>
                                                                </Grid>
                                                            </Grid>
                                                        </Grid> 
                                                    </Grid>
                                                </Grid>
                                            </Grid>
                                        </Stack>
                                    </Card>
                                </Grid>
                                {data.list.map((note) => (
                                    <Grid
                                        xs={12}
                                        item>
                                        <Card
                                            variant="elevation"
                                            sx={{padding: 2}}>
                                            <Stack
                                                alignItems="center"
                                                direction="row"
                                                gap={2}>
                                                <Avatar
                                                    sx={(theme) => ({
                                                        width: 48,
                                                        height: 48,
                                                        background: '#4E5B5E'
                                                    })}
                                                    variant="circular">
                                                    <SvgIcon sx={{width: 32, height: 32}}>
                                                        <g
                                                            strokeLinejoin="round" 
                                                            strokeLinecap="round"
                                                            strokeWidth="2"
                                                            stroke="#F3F3F3"
                                                            fill="none">
                                                            <path d="M8 9h8m-8 4h6m4-9a3 3 0 0 1 3 3v8a3 3 0 0 1-3 3h-5l-5 3v-3H6a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3z"/>
                                                        </g>
                                                    </SvgIcon>
                                                </Avatar>
                                                <Stack flex={1} alignItems="start">
                                                    <Chip label={{1: 'Baja', 2: 'Media', 3: 'Alta'}[note.rank as number]} color={{1: 'info', 2: 'warning', 3: 'error'}[note.rank as number] as 'info' | 'error' | 'warning'} variant="outlined" />
                                                    <Typography color="#232523">
                                                        {note.text}
                                                    </Typography>
                                                </Stack>
                                                <Stack
                                                    gap={2}
                                                    direction="row"
                                                    alignItems="start">
                                                    {(Boolean(note.file) && (
                                                        <IconButton
                                                            sx={(theme) => ({
                                                                margin: 0,
                                                                background: alpha(theme.palette.secondary.light, 0.16)
                                                            })}
                                                            title="Descargar documento"
                                                            onClick={(event) => {
                                                                window.open(`${BACK}/files/save/${note.file}`, '_blank');
                                                            }}>
                                                            <SvgIcon>
                                                                <g
                                                                    strokeLinejoin="round"
                                                                    strokeLinecap="round"
                                                                    strokeWidth="2"
                                                                    stroke="currentColor"
                                                                    fill="none">
                                                                    <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2M7 11l5 5l5-5m-5-7v12" />
                                                                </g>
                                                            </SvgIcon>
                                                        </IconButton>
                                                    ))}
                                                    <IconButton
                                                        sx={(theme) => ({
                                                            margin: 0,
                                                            background: alpha(theme.palette.secondary.light, 0.16)
                                                        })}
                                                        title="Editar"
                                                        onClick={(event) => {
                                                            setData((last) => ({
                                                                ...last,
                                                                form: {
                                                                    ...last.form,
                                                                    note: {
                                                                        ...last.form.note,
                                                                        item: data.item,
                                                                        part: note,
                                                                        open: true,
                                                                        text: null,
                                                                        data: {
                                                                            rank: note.rank,
                                                                            text: note.text
                                                                        }
                                                                    }
                                                                }
                                                            }));
                                                        }}>
                                                        <SvgIcon>
                                                            <g
                                                                strokeLinejoin="round"
                                                                strokeLinecap="round"
                                                                strokeWidth="2"
                                                                stroke="currentColor"
                                                                fill="none">
                                                                <path d="M4 20h4L18.5 9.5a2.828 2.828 0 1 0-4-4L4 16zm9.5-13.5l4 4" />
                                                            </g>
                                                        </SvgIcon>
                                                    </IconButton>
                                                    <IconButton
                                                        sx={(theme) => ({
                                                            margin: 0,
                                                            background: alpha(theme.palette.secondary.light, 0.16)
                                                        })}
                                                        title="Eliminar"
                                                        onClick={(event) => {
                                                            setDialog(
                                                                <Stack direction="column">
                                                                    <Typography gutterBottom>
                                                                    ¿Deseas eliminar el comentario?
                                                                    </Typography>
                                                                </Stack>
                                                                ,
                                                                'Eliminar',
                                                                'Eliminar comentario',
                                                                'sm',
                                                                {
                                                                    type: 'error',
                                                                    text: 'Eliminar',
                                                                    task: (wait: (flag: boolean) => void, hide: (flag: boolean) => void) => {
                                                                        Store.drop(data.item?.hash, (done: boolean) => {
                                                                            hide(true);
                        
                                                                            if (done) {
                                                                                setData((last) => ({
                                                                                    ...last,
                                                                                    list: last.list.filter((item: any) => ((item == note) == false))
                                                                                }));
                        
                                                                                setAlert('El comentario fue eliminado.', 'success');
                                                                            } else {
                                                                                setAlert('No se pudo eliminar el comentario.', 'error');
                                                                            }
                                                                        }, 'note', note.hash);
                        
                                                                        wait(true);
                                                                    }
                                                                }
                                                            );
                                                        }}>
                                                        <SvgIcon>
                                                            <g
                                                                strokeLinejoin="round"
                                                                strokeLinecap="round"
                                                                strokeWidth="2"
                                                                stroke="#F84336"
                                                                fill="none">
                                                                <path d="M4 7h16m-10 4v6m4-6v6M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2l1-12M9 7V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v3" />
                                                            </g>
                                                        </SvgIcon>
                                                    </IconButton>
                                                </Stack>
                                            </Stack>
                                        </Card>
                                    </Grid>
                                ))}
                            </Grid>
                        ) : (Boolean(fail) ? (
                            <Fail text="Lo sentimos">
                                {(fail ?? 'Se presentó una excepción no esperada.')}
                            </Fail>
                        ) : (
                            <Box
                                justifyContent="center"
                                alignItems="center"
                                display="flex"
                                flex={1}>
                                <CircularProgress size={48} />
                            </Box>
                        )))}
                    </Grid>
                </Grid>
                <Dialog
                    open={(data.form.note.open)}
                    onClose={(event, reason) => {
                        if ((((reason == 'backdropClick') && data.form.note.wait) == false)) {
                            setData((last) => ({
                                ...last,
                                form: {
                                    ...last.form,
                                    note: {
                                        ...last.form.note,
                                        open: false
                                    }
                                }
                            }));
                        }
                    }}
                    maxWidth="sm"
                    fullWidth>
                    <DialogTitle
                        sx={{px: 3, pt: 4, pb: 1}}
                        alignItems="center"
                        justifyContent="center">
                        <Typography
                            color="text.primary"
                            variant="h5"
                            gutterBottom>
                            Comentario
                        </Typography>
                        <Typography
                            color="text.secondary"
                            gutterBottom>
                            Editar comentario.
                        </Typography>
                    </DialogTitle>
                    <DialogContent>
                        <Grid
                            spacing={1}
                            container>
                            {(Boolean(data.form.note.text) && (
                                <Grid
                                    xs={12}
                                    item>
                                    <Alert severity="error">
                                        {data.form.note.text}
                                    </Alert>
                                </Grid>
                            ))}
                            <Grid
                                xs={12}
                                item>
                                <Form
                                    data={data.form.note.data}
                                    fail={data.form.note.list}
                                    wait={data.form.note.wait}
                                    lock={data.form.note.wait}
                                    flat={true}
                                    view={{
                                        data: [
                                            {
                                                type: 'menu',
                                                size: 'full',
                                                name: 'rank',
                                                text: 'Prioridad',
                                                hint: 'Seleccione una opción',
                                                bind: 'La opción es requerida.',
                                                list: [
                                                    {item: 1, text: 'Baja'},
                                                    {item: 2, text: 'Media'},
                                                    {item: 3, text: 'Alta'}
                                                ]
                                            },
                                            {
                                                type: 'area',
                                                size: 'full',
                                                name: 'text',
                                                text: 'Comentario',
                                                hint: 'Comentario acerca del reporte',
                                                bind: 'El campo es requerido.'
                                            }
                                        ]
                                    } as any}
                                    quit={{
                                        task: (step, exit) => {
                                            setData((last) => ({...last, form: {...last.form, note: {...last.form.note, open: false}}}));
                                        }
                                    }}
                                    save={{
                                        text: 'Guardar',
                                        task: (form: any, step?: number, save?: boolean) => {
                                            Store.save(data.form.note.item.hash, form, (done: boolean) => {
                                                if (done) {
                                                    data.form.note.part.rank = form.rank;

                                                    data.form.note.part.text = form.text;

                                                    setAlert('El comentario fue actualizado correctamente.', 'success');

                                                    setData((last) => ({
                                                        ...last,
                                                        list: [...last.list],
                                                        form: {
                                                            ...last.form,
                                                            note: {
                                                                ...last.form.note,
                                                                open: false,
                                                                wait: false
                                                            }
                                                        }
                                                    }));
                                                } else {
                                                    setData((last) => ({
                                                        ...last,
                                                        form: {
                                                            ...last.form,
                                                            note: {
                                                                ...last.form.note,
                                                                wait: false,
                                                                text: 'El comentario no pudo ser actualizado correctamente.'
                                                            }
                                                        }
                                                    }));
                                                }
                                            }, 'note', data.form.note.part.hash);

                                            setData((last) => ({
                                                ...last,
                                                form: {
                                                    ...last.form,
                                                    note: {
                                                        ...last.form.note,
                                                        wait: true,
                                                        text: null,
                                                        list: null
                                                    }
                                                }
                                            }));
                                        }
                                    }} />
                            </Grid>
                        </Grid>
                    </DialogContent>
                </Dialog>
            </Fragment>
        );
    }

    export const Seek = () => {
        const {
            item,
            load
        } = useParams();
    
        const {
            setAlert,
            setTitle,
            setDialog
        } = useApplication();

        const [data, setData] = useState<{
            list: Array<Hash<any>>,
            item: Null<Hash<any>>,
            form: Hash<any>,
            wait: boolean,
            lock: boolean,
            done: boolean
        }>({
            form: {
                note: {
                    wait: false,
                    open: false,
                    item: null,
                    text: null,
                    list: null,
                    data: {
                        term: null,
                        rate: null,
                        rank: null,
                        text: null,
                        file: null,
                        mail: null
                    }
                }
            },
            wait: false,
            lock: false,
            done: false,
            item: null,
            list: []
        });

        const [done, setDone] =  useState(false);

        const [fail, setFail] =  useState(false);

        useEffect(() => {
            Store.seek(item, load, (done, data) => {
                setTimeout(() => {
                    if (done) {
                        setData((last) => ({
                            ...last,
                            done: true,
                            item: Boolean(item) ? {
                                make: data?.item,
                                hash: data?.hash,
                                name: data?.name,
                                icon: data?.icon
                            } : null,
                            list: data?.data ?? data
                        }));

                        setDone(true);
                    } else {
                        setFail(true);
                    }
                }, 300);
            });

            setTitle('Reporte');
        }, [item, load]);
    
        return (
            <Fragment>
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
                            Reporte
                        </Typography>
                        {(data.done ? (
                            <Typography
                                color="text.secondary"
                                gutterBottom>
                                Resultados de {((data.item?.name && `"${data.item?.name}"`) ?? 'tus marcas')}
                            </Typography>
                        ) : (
                            <Typography
                                color="text.secondary"
                                gutterBottom>
                                Reporte de marca
                            </Typography>
                        ))}
                    </Grid>
                    <Grid
                        display="flex"
                        flex={1}
                        item>
                        {(fail ? (
                            <Card sx={{padding: '128px 0px 64px 0px'}}>
                                <Box sx={{display: 'flex', flexDirection: 'column', alignItems: 'center'}}>
                                    <Avatar sx={{mb: 2, width: 96, height: 96, color: '#B3B3B3', background: '#F3F3F3'}}>
                                        <SvgIcon sx={{width: '64px', height: '64px'}}>
                                            <g
                                                strokeLinejoin="round"
                                                strokeLinecap="round"
                                                strokeWidth="2"
                                                stroke="#B3B3B3"
                                                fill="none">
                                                <path d="M4 6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2z"/>
                                                <path d="M4 13h3l3 3h4l3-3h3"/>
                                            </g>
                                        </SvgIcon>
                                    </Avatar>
                                    <Typography
                                        color="text.primary"
                                        variant="h4"
                                        gutterBottom>
                                        No encontrado
                                    </Typography>
                                    <Typography
                                        color="text.secondary"
                                        gutterBottom>
                                        No se pudo encontrar el te.
                                    </Typography>
                                </Box>
                            </Card>
                        ) : (done ? (
                            <Data
                                seek={'Id'}
                                name=""
                                dash={[
                                    {
                                        icon: ['M3 10a7 7 0 1 0 14 0a7 7 0 1 0-14 0m18 11l-6-6'],
                                        hint: 'Buscar',
                                        name: 'Find',
                                        view: (item: any, lock: boolean) => (
                                            <Button
                                                sx={{padding: '2px 6px 2px 16px'}}
                                                color="primary"
                                                variant="contained"
                                                disabled={lock}
                                                startIcon={(
                                                    <SvgIcon sx={{width: '24px', height: '24px'}}>
                                                        <g
                                                            strokeLinejoin="round"
                                                            strokeLinecap="round"
                                                            strokeWidth="2"
                                                            stroke="currentColor"
                                                            fill="none">
                                                            <path d="M8 9h8m-8 4h6m-1.99 5.594L8 21v-3H6a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3h12a3 3 0 0 1 3 3v5.5M16 19h6m-3-3v6" />
                                                        </g>
                                                    </SvgIcon>
                                                )}
                                                endIcon={(
                                                    <SvgIcon sx={{width: '38px', height: '38px'}}>
                                                        <g
                                                            strokeLinejoin="round"
                                                            strokeLinecap="round"
                                                            strokeWidth="2"
                                                            stroke="none"
                                                            fill="currentColor">
                                                            <path d="M4.929 4.929A10 10 0 1 1 19.07 19.07A10 10 0 0 1 4.93 4.93zM13 9a1 1 0 1 0-2 0v2H9a1 1 0 1 0 0 2h2v2a1 1 0 1 0 2 0v-2h2a1 1 0 1 0 0-2h-2z" />
                                                        </g>
                                                    </SvgIcon>
                                                )}
                                                onClick={(event) => {
                                                    setData((last) => ({
                                                        ...last,
                                                        form: {
                                                            ...last.form,
                                                            note: {
                                                                ...last.form.note,
                                                                open: true,
                                                                text: null,
                                                                list: null,
                                                                item: last.item ?? item,
                                                                data: {
                                                                    term: item.term,
                                                                    rate: item.rate,
                                                                    rank: null,
                                                                    text: null,
                                                                    file: null
                                                                }
                                                            }
                                                        }
                                                    }));
                                                }}>
                                                Comentario
                                            </Button>
                                        )
                                    }
                                ]}
                                data={[
                                    {
                                        item: 'name',
                                        name: 'Marca',
                                        join: item ? false : true
                                    },
                                    {
                                        item: 'text',
                                        name: 'Nombre',
                                        show: true,
                                        sort: true
                                    },
                                    {
                                        item: 'card',
                                        name: 'Expediente',
                                        size: 180,
                                        show: true,
                                        sort: true
                                    },
                                    {
                                        item: 'sort',
                                        name: 'Clases',
                                        size: 290,
                                        show: true,
                                        sort: true,
                                        cast: (item: any) => (item.sort?.join(', '))
                                    },
                                    {
                                        item: 'type',
                                        edge: 'center',
                                        name: 'Tipo',
                                        show: true,
                                        sort: true,
                                        size: 180,
                                        pick: [
                                            {
                                                item: 0,
                                                text: 'N/A'
                                            },
                                            {
                                                item: 1,
                                                text: 'Mixta'
                                            },
                                            {
                                                item: 2,
                                                text: 'Nominativa'
                                            },
                                            {
                                                item: 3,
                                                text: 'Figurativa'
                                            },
                                            {
                                                item: 4,
                                                text: '3D'
                                            },
                                            {
                                                item: 5,
                                                text: 'Sonido'
                                            },
                                            {
                                                item: 6,
                                                text: 'Tridimensional mixta'
                                            },
                                            {
                                                item: 7,
                                                text: 'LEMA COMERCIAL'
                                            }
                                        ]
                                    },
                                    {
                                        item: 'rank',
                                        edge: 'center',
                                        name: 'Estado',
                                        show: true,
                                        sort: true,
                                        size: 210,
                                        pick: [
                                            {
                                                item: 0,
                                                tint: '9E9E9E',
                                                text: 'N/A'
                                            },
                                            {
                                                item: 1,
                                                tint: 'D84315',
                                                text: 'Negada'
                                            },
                                            {
                                                item: 2,
                                                tint: '1976D2',
                                                text: 'Publicada'
                                            },
                                            {
                                                item: 3,
                                                tint: '2E7D32',
                                                text: 'Registrada'
                                            },
                                            {
                                                item: 4,
                                                tint: 'F9B825',
                                                text: 'Bajo examen de fondo'
                                            },
                                            {
                                                item: 5,
                                                tint: '880E4F',
                                                text: 'Cancelada'
                                            },
                                            {
                                                item: 6,
                                                tint: 'D32F2F',
                                                text: 'Renuncia total'
                                            },
                                            {
                                                item: 7,
                                                tint: '0277BD',
                                                text: 'Concepto de viabilidad'
                                            },
                                            {
                                                item: 8,
                                                tint: 'FDD835',
                                                text: 'Bajo examen de forma'
                                            },
                                            {
                                                item: 9,
                                                tint: 'D84315',
                                                text: 'Con oposición'
                                            },
                                            {
                                                item: 10,
                                                tint: '2E7D32',
                                                text: 'Caducada'
                                            }
                                        ]
                                    },
                                    {
                                        item: 'date',
                                        name: 'Fecha',
                                        edge: 'right',
                                        show: true,
                                        sort: true,
                                        size: 180,
                                        cast: (item: any) => (moment(item.date).format('DD/MM/YY'))
                                    }
                                ]}
                                none={{
                                    text: 'Sin coincidencias',
                                    note: 'No se encontraron coincidencias disponibles.'
                                }}
                                list={data.list}
                                tint={(item: any, next: any) => ({
                                    5: '#E53935',
                                    4: '#E65100',
                                    3: '#FF8F00',
                                    2: '#FDD835'
                                }[item.rate as 2 | 3 | 4 | 5])}
                                view={(item: any, size?: number) => (
                                    <Typography
                                        color="primary"
                                        variant="h6"
                                        gutterBottom>
                                        {`${item.name} (${size})`}
                                    </Typography>
                                )}
                                page={0} />
                        ) : (
                            <Box
                                justifyContent="center"
                                alignItems="center"
                                display="flex"
                                flex={1}>
                                <CircularProgress size={48} />
                            </Box>
                        )))}
                    </Grid>
                </Grid>
                <Dialog
                    open={(data.form.note.open)}
                    onClose={(event, reason) => {
                        if ((((reason == 'backdropClick') && data.form.note.wait) == false)) {
                            setData((last) => ({
                                ...last,
                                form: {
                                    ...last.form,
                                    note: {
                                        ...last.form.note,
                                        open: false
                                    }
                                }
                            }));
                        }
                    }}
                    maxWidth="sm"
                    fullWidth>
                    <DialogTitle
                        sx={{px: 3, pt: 4, pb: 1}}
                        alignItems="center"
                        justifyContent="center">
                        <Typography
                            color="text.primary"
                            variant="h5"
                            gutterBottom>
                            Comentario
                        </Typography>
                        <Typography
                            color="text.secondary"
                            gutterBottom>
                            Agregar comentario.
                        </Typography>
                    </DialogTitle>
                    <DialogContent>
                        <Grid
                            spacing={1}
                            container>
                            {(Boolean(data.form.note.text) && (
                                <Grid
                                    xs={12}
                                    item>
                                    <Alert severity="error">
                                        {data.form.note.text}
                                    </Alert>
                                </Grid>
                            ))}
                            <Grid
                                xs={12}
                                item>
                                <Form
                                    data={data.form.note.data}
                                    fail={data.form.note.list}
                                    wait={data.form.note.wait}
                                    lock={data.form.note.wait}
                                    flat={true}
                                    view={{
                                        data: [
                                            {
                                                type: 'menu',
                                                size: 'full',
                                                name: 'rank',
                                                text: 'Prioridad',
                                                hint: 'Seleccione una opción',
                                                bind: 'La opción es requerida.',
                                                list: [
                                                    {item: 1, text: 'Baja'},
                                                    {item: 2, text: 'Media'},
                                                    {item: 3, text: 'Alta'}
                                                ]
                                            },
                                            {
                                                type: 'area',
                                                size: 'full',
                                                name: 'text',
                                                text: 'Comentario',
                                                hint: 'Comentario acerca del reporte',
                                                bind: 'El campo es requerido.'
                                            },
                                            {
                                                type: 'file',
                                                size: 'full',
                                                name: 'file',
                                                text: 'Documento',
                                                hint: 'El documento no puede pesar más de 6 MB'
                                            },
                                            {
                                                type: 'turn',
                                                size: 'full',
                                                name: 'mail',
                                                text: 'Enviar correo electrónico'
                                            }
                                        ]
                                    } as any}
                                    quit={{
                                        task: (step, exit) => {
                                            setData((last) => ({...last, form: {...last.form, note: {...last.form.note, open: false}}}));
                                        }
                                    }}
                                    save={{
                                        text: 'Guardar',
                                        task: (form: any, step?: number, save?: boolean) => {
                                            Store.make(form, (done: boolean, data: any) => {
                                                if (done) {
                                                    setData((last) => ({
                                                        ...last,
                                                        form: {
                                                            ...last.form,
                                                            note: {
                                                                ...last.form.note,
                                                                open: false,
                                                                wait: false
                                                            }
                                                        }
                                                    }));

                                                    setAlert((data?.text ?? 'El comentario fue guardado correctamente.'), 'success');
                                                } else {
                                                    setData((last) => ({
                                                        ...last,
                                                        form: {
                                                            ...last.form,
                                                            note: {
                                                                ...last.form.note,
                                                                wait: false,
                                                                text: data?.text ?? 'El comentario no pudo ser guardado correctamente.',
                                                                list: data?.list ?? {}
                                                            }
                                                        }
                                                    }));
                                                }
                                            }, data.form.note.item.make, 'note');

                                            setData((last) => ({
                                                ...last,
                                                form: {
                                                    ...last.form,
                                                    note: {
                                                        ...last.form.note,
                                                        wait: true,
                                                        text: null,
                                                        list: null
                                                    }
                                                }
                                            }));
                                        }
                                    }} />
                            </Grid>
                        </Grid>
                    </DialogContent>
                </Dialog>
            </Fragment>
        );
    }

    export const Edit = () => {
        const {setAlert, setTitle} = useApplication();

        const navigate = useNavigate();

        const {session} = useSession();

        const [form, setForm] = useState<{
            wait: boolean,
            lock: boolean,
            fail: Hash<string>,
            item: Hash<any>,
            load: Hash<any>,
            data: Hash<any>
        }>({
            wait: false,
            lock: false,
            load: {
                Kind: {
                    wait: false,
                    fail: null,
                    find: null,
                    list: null
                }
            },
            data: {
                kind: [],
                seek: [],
                risk: [],
                rate: [],
                sort: [],
                data: [],
                type: null,
                bind: null,
                rank: null,
                link: null,
                name: null,
                proxy: null,
                number: null,
                titular: null,
                reference: null,
                concession: null,
                validation: null,
                information: null,
                publication: null,
                introduction: null,
                presentation: null
            },
            item: {},
            fail: {}
        });

        const view: any = {
            data: [
                {
                    type: 'file',
                    snap: true,
                    path: (icon: any) => (`${BACK}/snaps/${icon}`),
                    name: 'icon',
                    text: 'Subir nueva imágen'
                },
                {
                    step: 0,
                    line: true,
                    name: 'Main',
                    text: 'Básica',
                    hint: 'Información básica'
                },
                {
                    type: 'menu',
                    size: 'half',
                    name: 'type',
                    text: 'Tipo',
                    hint: 'Seleccione una opción',
                    bind: 'La opción es requerida.',
                    list: [
                        {item: 1, text: 'Mixta'},
                        {item: 2, text: 'Nominativa'},
                        {item: 3, text: 'No Figurativa'},
                        {item: 4, text: '3D'},
                        {item: 5, text: 'Sonido'},
                        {item: 6, text: 'Tridimensional mixta'},
                        {item: 7, text: 'LEMA COMERCIAL'}
                    ]
                },
                {
                    type: 'list',
                    size: 'half',
                    name: 'bind',
                    text: 'Empresa',
                    list: session.heap?.firm?.map((next: any) => ({item: next.item, text: next.name, hint: next.card})) ?? []
                },
                {
                    type: 'text',
                    size: 'half',
                    name: 'name',
                    text: 'Nombre',
                    hint: 'Nombre de la marca',
                    bind: 'El campo es requerido.'
                },
                {
                    type: 'text',
                    size: 'tiny',
                    name: 'number',
                    text: 'Número del caso',
                    hint: 'Número del caso'
                },
                {
                    type: 'menu',
                    size: 'tiny',
                    name: 'rank',
                    text: 'Estado del caso',
                    hint: 'Seleccione una opción.',
                    bind: 'El campo es requerido.',
                    list: [
                        {item: 1, text: 'Negada'},
                        {item: 2, text: 'Publicada'},
                        {item: 3, text: 'Registrada'},
                        {item: 4, text: 'Bajo examen de fondo'},
                        {item: 5, text: 'Cancelada'},
                        {item: 6, text: 'Renuncia total'},
                        {item: 7, text: 'Concepto de viabilidad'},
                        {item: 8, text: 'Bajo examen de forma'},
                        {item: 9, text: 'Con oposición'},
                        {item: 10, text: 'Caducada'}
                    ]
                },
                {
                    type: 'text',
                    size: 'half',
                    name: 'title',
                    text: 'Título del caso',
                    hint: 'Título del caso'
                },
                {
                    type: 'text',
                    size: 'half',
                    name: 'link',
                    text: 'Enlace del caso',
                    hint: 'Enlace del caso'
                },
                {
                    type: 'text',
                    size: 'half',
                    name: 'titular',
                    text: 'Titular',
                    hint: 'Nombre del titular'
                },
                {
                    type: 'text',
                    size: 'half',
                    name: 'reference',
                    text: 'Referencia del solicitante',
                    hint: 'Referencia del solicitante'
                },
                {
                    type: 'date',
                    size: 'half',
                    name: 'validation',
                    text: 'Vigencia'
                },
                {
                    type: 'date',
                    size: 'half',
                    name: 'publication',
                    text: 'Fecha de publicación'
                },
                {
                    type: 'date',
                    size: 'half',
                    name: 'presentation',
                    text: 'Fecha de radicación'
                },
                {
                    type: 'date',
                    size: 'half',
                    name: 'registration',
                    text: 'Fecha de concesión'
                },
                {
                    span: true,
                    type: 'list',
                    size: 'full',
                    name: 'kind',
                    text: 'Clases',
                    list: form.load.Kind.list?.map((next: any) => ({item: next.code, text: next.code, note: next.note, more: next.more, hint: (next.code == next.name) ? null : next.name})) ?? []
                },
                {
                    type: 'text',
                    size: 'full',
                    name: 'proxy',
                    text: 'Apoderado',
                    hint: 'Nombre del apoderado'
                },
                {
                    type: 'area',
                    size: 'full',
                    name: 'notice',
                    text: 'Aviso importante'
                },
                {
                    type: 'area',
                    size: 'full',
                    name: 'information',
                    text: 'Otra información'
                },
                {
                    line: true,
                    name: 'Sort',
                    text: 'Conceptos',
                    hint: 'Clases para el concepto de viabilidad.'
                },
                {
                    tile: true,
                    type: 'text',
                    size: 'full',
                    name: 'sort',
                    text: 'Agergar concepto',
                    form: {
                        size: 0,
                        high: 16,
                        list: [
                            {
                                type: 'list',
                                size: 'full',
                                name: 'code',
                                text: 'Clase',
                                bind: 'El campo es requerido.',
                                list: form.load.Kind.list?.map((next: any) => ({item: next.code, text: next.code, note: next.note, more: next.more, hint: (next.code == next.name) ? null : next.name})) ?? [],
                                live: (data: any, form: any, item: any) => {
                                    form.sort[item] = {
                                        ...form.sort[item],
                                        note: data.note,
                                        more: data.more
                                    };

                                    setForm((last) => ({
                                        ...last,
                                        data: {
                                            ...last.data,
                                            ...form
                                        }
                                    }));
                                }
                            },
                            {
                                type: 'area',
                                size: 'full',
                                name: 'note',
                                text: 'Descripción',
                                bind: 'El campo es requerido.'
                            },
                            {
                                type: 'area',
                                size: 'full',
                                name: 'more',
                                text: 'Incluye'
                            }
                        ],
                        push: (next: number) => ({code: null, note: null, more: null})
                    }
                },
                {
                    line: true,
                    name: 'Data',
                    text: 'Catálogo',
                    hint: 'Descripción productos y servicios.'
                },
                {
                    tile: true,
                    type: 'text',
                    size: 'full',
                    name: 'data',
                    text: 'Agergar descripción',
                    form: {
                        size: 0,
                        high: 16,
                        list: [
                            {
                                type: 'text',
                                name: 'code',
                                text: 'Código',
                                size: 'tiny',
                                hint: 'Código del producto o servicio',
                                bind: 'El código es requerido.'
                            },
                            {
                                type: 'text',
                                name: 'text',
                                text: 'Descripción',
                                size: 'wide',
                                hint: 'Descripción del producto o servicio',
                                bind: 'La Descripción es requerida.'
                            }
                        ],
                        push: (next: number) => ({code: null, text: null})
                    }
                },
                {
                    line: true,
                    name: 'Data',
                    text: 'Oposición',
                    hint: 'Marcas similares con riesgo de oposición.'
                },
                {
                    type: 'area',
                    size: 'full',
                    name: 'introduction',
                    text: 'Introducción'
                },
                {
                    tile: true,
                    type: 'text',
                    size: 'full',
                    name: 'risk',
                    text: 'Agergar oposición',
                    form: {
                        size: 0,
                        high: 16,
                        list: [
                            {
                                type: 'file',
                                snap: true,
                                path: (icon: any) => (`${BACK}/snaps/${icon}`),
                                name: 'icon',
                                text: 'Subir nueva imágen'
                            },
                            {
                                type: 'menu',
                                size: 'tiny',
                                name: 'type',
                                text: 'Tipo',
                                hint: 'Seleccione una opción.',
                                bind: 'El campo es requerido.',
                                list: [
                                    {item: 1, text: 'Mixta'},
                                    {item: 2, text: 'Nominativa'},
                                    {item: 3, text: 'No Figurativa'},
                                    {item: 4, text: '3D'},
                                    {item: 5, text: 'Sonido'},
                                    {item: 6, text: 'Tridimensional mixta'},
                                    {item: 7, text: 'LEMA COMERCIAL'}
                                ]
                            },
                            {
                                type: 'text',
                                name: 'code',
                                text: 'Expediente',
                                size: 'tiny',
                                bind: 'El campo es requerido.'
                            },
                            {
                                type: 'text',
                                name: 'name',
                                text: 'Nombre',
                                size: 'half',
                                bind: 'El campo es requerido.'
                            },
                            {
                                type: 'text',
                                name: 'skip',
                                text: 'Titular',
                                size: 'half',
                                bind: 'El campo es requerido.'
                            },
                            {
                                type: 'date',
                                size: 'half',
                                name: 'date',
                                text: 'Vigencia',
                                bind: 'El campo es requerido.'
                            },
                            {
                                span: true,
                                type: 'list',
                                size: 'full',
                                name: 'sort',
                                text: 'Clases',
                                list: form.load.Kind.list?.map((next: any) => ({item: next.code, text: next.code, note: next.note, more: next.more, hint: (next.code == next.name) ? null : next.name})) ?? []
                            },
                            {
                                type: 'area',
                                name: 'note',
                                text: 'Análisis de Riesgo',
                                size: 'full',
                                bind: 'El campo es requerido.'
                            },
                            {
                                type: 'area',
                                name: 'zone',
                                text: 'Cobertura',
                                size: 'full'
                            }
                        ],
                        push: (next: number) => ({code: null, name: null, skip: null, date: null, type: null, note: null, zone: null, icon: null, sort: []})
                    }
                },
                {
                    line: true,
                    name: 'Data',
                    text: 'Viabilidad',
                    hint: 'Viabilidad de registro estimada.'
                },
                {
                    tile: true,
                    type: 'text',
                    size: 'full',
                    name: 'rate',
                    text: 'Agergar viabilidad',
                    form: {
                        size: 0,
                        high: 16,
                        list: [
                            {
                                span: true,
                                type: 'list',
                                size: 'full',
                                name: 'sort',
                                text: 'Clases',
                                bind: 'El campo es requerido.',
                                list: form.load.Kind.list?.map((next: any) => ({item: next.code, text: next.code, note: next.note, more: next.more, hint: (next.code == next.name) ? null : next.name})) ?? []
                            },
                            {
                                type: 'text',
                                name: 'load',
                                text: 'Porcentaje',
                                size: 'tiny',
                                bind: 'El campo es requerido.'
                            },
                            {
                                type: 'text',
                                name: 'note',
                                text: 'Resumen',
                                size: 'wide',
                                bind: 'El campo es requerido.'
                            }
                        ],
                        push: (next: number) => ({load: null, note: null, sort: []})
                    }
                },
                {
                    line: true,
                    name: 'Data',
                    text: 'Resultados',
                    hint: 'Resultados de la búsqueda en SIC.'
                },
                {
                    type: 'file',
                    kind: ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.google-apps.spreadsheet', 'application/vnd.ms-excel'],
                    name: 'bulk',
                    hint: 'Seleccione un documento para importar.',
                    live: (pick: Null<File>) => {
                        if (pick) {
                            const book = new Excel.Workbook();

                            const file = new FileReader();

                            const head: Array<Hash<any>> = [
                                {slot: null, name: 'Expediente'},
                                {slot: null, name: 'Denominacion'},
                                {slot: null, name: 'Vigencia'},
                                {slot: null, name: 'Estado'},
                                {slot: null, name: 'Titular'},
                                {slot: null, name: 'Clases'}
                            ];

                            let push = false;

                            let list: Array<Hash<any>> = [];

                            file.readAsArrayBuffer(pick);

                            file.onload = (event) => {
                                if (event?.target?.result) {
                                    book.xlsx.load(event.target.result as ArrayBuffer).then(work => {
                                        work.eachSheet((sheet) => {
                                            sheet.eachRow((data) => {
                                                if ((data.values instanceof Array)) {
                                                    if (push) {
                                                        list.push({
                                                            code: data.values[head[0].slot] ?? null,
                                                            name: data.values[head[1].slot] ?? null,
                                                            skip: data.values[head[4].slot] ?? null,
                                                            date: moment(data.values[head[2].slot]?.toString()).format('YYYY-MM-DD'),
                                                            rank: {
                                                                'negada': 1,
                                                                'publicada': 2,
                                                                'concedida': 3,
                                                                'registrada': 3,
                                                                'bajo examen de fondo': 4,
                                                                'vancelada': 5,
                                                                'renuncia total': 6,
                                                                'concepto de viabilidad': 7,
                                                                'bajo examen de forma': 8,
                                                                'con oposición': 9,
                                                                'caducada': 10
                                                            }[(data.values[head[3].slot]?.toString()?.toLocaleLowerCase() ?? '')] ?? null,
                                                            sort: data.values[head[5].slot]?.toString()?.split(',')?.filter((item: string) => (Boolean(item.trim()))) ?? []
                                                        });
                                                    } else {
                                                        data.values.forEach((data, next) => {
                                                            let item = head.find((item: any) => (item.name == data));

                                                            if (item) {
                                                                item.slot = next;
                                                            }
                                                        });

                                                        push = head.every((item: any) => (Boolean(item.slot)));
                                                    }
                                                }
                                            })
                                        });

                                        setForm((last) => ({
                                            ...last,
                                            data: {
                                                ...last.data,
                                                seek: list,
                                                bulk: null
                                            }
                                        }));
                                    })
                                }
                            }
                        }
                    }
                },
                {
                    tile: true,
                    type: 'text',
                    size: 'full',
                    name: 'seek',
                    text: 'Agergar resultado',
                    form: {
                        size: 0,
                        high: 16,
                        list: [
                            {
                                type: 'menu',
                                size: 'tiny',
                                name: 'rank',
                                text: 'Estado',
                                hint: 'Seleccione una opción.',
                                bind: 'El campo es requerido.',
                                list: [
                                    {item: 1, text: 'Negada'},
                                    {item: 2, text: 'Publicada'},
                                    {item: 3, text: 'Registrada'},
                                    {item: 4, text: 'Bajo examen de fondo'},
                                    {item: 5, text: 'Cancelada'},
                                    {item: 6, text: 'Renuncia total'},
                                    {item: 7, text: 'Concepto de viabilidad'},
                                    {item: 8, text: 'Bajo examen de forma'},
                                    {item: 9, text: 'Con oposición'},
                                    {item: 10, text: 'Caducada'}
                                ]
                            },
                            {
                                type: 'text',
                                name: 'code',
                                text: 'Expediente',
                                size: 'tiny',
                                bind: 'El campo es requerido.'
                            },
                            {
                                type: 'text',
                                name: 'name',
                                text: 'Nombre',
                                size: 'half',
                                bind: 'El campo es requerido.'
                            },
                            {
                                type: 'text',
                                name: 'skip',
                                text: 'Titular',
                                size: 'half',
                                bind: 'El campo es requerido.'
                            },
                            {
                                type: 'date',
                                size: 'half',
                                name: 'date',
                                text: 'Vigencia',
                                bind: 'El campo es requerido.'
                            },
                            {
                                span: true,
                                type: 'list',
                                size: 'full',
                                name: 'sort',
                                text: 'Clases',
                                list: form.load.Kind.list?.map((next: any) => ({item: next.code, text: next.code, note: next.note, more: next.more, hint: (next.code == next.name) ? null : next.name})) ?? []
                            }
                        ],
                        push: (next: number) => ({code: null, name: null, skip: null, date: null, rank: null, sort: []})
                    }
                }
            ]
        };

        const [done, setDone] =  useState(0);

        const {item} = useParams();

        useEffect(() => {
            Store.find(`${item}`, '', (done, data) => {
                setTimeout(() => {
                    if (done) {
                        setDone(1);

                        setForm((last) => ({
                            ...last,
                            data: {
                                ...data.item,
                                icon: null
                            },
                            item: data.item
                        }));
                    } else {
                        setAlert('No se pudo cargar la información.', 'error');

                        setDone(2);
                    }
                }, 300);
            });

            Kinds.load(0, null, null, null, null, null, (done, data) => {
                if (done) {
                    setForm((last) => ({
                        ...last,
                        load: {
                            ...last.load,
                            Kind: {
                                ...last.load.Kind,
                                list: data.data,
                                wait: false
                            }
                        }
                    }));
                } else {
                    setForm((last) => ({
                        ...last,
                        load: {
                            ...last.load,
                            Kind: {
                                ...last.load.Kind,
                                wait: false,
                                fail: true
                            }
                        }
                    }));
                }
            });
        }, [item]);

        useEffect(() => {
            setTitle('Editar Marca');
        }, []);

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
                        Editar Marca
                    </Typography>
                    <Typography
                        color="text.secondary"
                        gutterBottom>
                        Módulo de vigilancia de marcas.
                    </Typography>
                </Grid>
                <Grid
                    display="flex"
                    flex={1}
                    item>
                    {(Boolean(done) ? (
                        ((done == 1) ? (
                            <Form
                                view={view}
                                data={form.data}
                                fail={form.fail}
                                wait={form.wait}
                                lock={form.lock}
                                quit={{
                                    text: 'Cancelar',
                                    task: () => {
                                        navigate('/vigilancia');
                                    }
                                }}
                                save={{
                                    text: 'Guardar',
                                    task: (form: any) => {
                                        setForm((last) => ({
                                            ...last,
                                            lock: true,
                                            fail: {}
                                        }));

                                        Store.save(`${item}`, form, (done: boolean, data: any) => {
                                            if (done) {
                                                setAlert((data?.text ?? 'El registro fue actualizado correctamente.'), 'success');

                                                setForm((last) => ({
                                                    ...last,
                                                    lock: false
                                                }));

                                                navigate('/vigilancia');
                                            } else {
                                                setAlert((data?.text ?? 'El registro no pudo ser actualizado.'), 'error');

                                                setForm((last) => ({
                                                    ...last,
                                                    lock: false,
                                                    fail: data.form,
                                                    data: {
                                                        ...last.data,
                                                        ...form
                                                    }
                                                }));
                                            }
                                        });
                                    }
                                }}
                            />
                        ) : (
                            <Card sx={{padding: '128px 0px 64px 0px'}}>
                                <Box sx={{display: 'flex', flexDirection: 'column', alignItems: 'center'}}>
                                    <Avatar sx={{mb: 2, width: 96, height: 96, color: '#B3B3B3', background: '#F3F3F3'}}>
                                        <SvgIcon sx={{width: '64px', height: '64px'}}>
                                            <g
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                stroke="#B3B3B3"
                                                stroke-width="2"
                                                stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path d="M4 6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2z"/>
                                                <path d="M4 13h3l3 3h4l3-3h3"/>
                                            </g>
                                        </SvgIcon>
                                    </Avatar>
                                    <Typography
                                        color="text.primary"
                                        variant="h4"
                                        gutterBottom>
                                        No encontrado
                                    </Typography>
                                    <Typography
                                        color="text.secondary"
                                        gutterBottom>
                                        El objeto no pudo ser encontrado.
                                    </Typography>
                                </Box>
                            </Card>
                        ))
                    ) : (
                        <Box
                            justifyContent="center"
                            alignItems="center"
                            display="flex"
                            flex={1}>
                            <CircularProgress size={48} />
                        </Box>
                    ))}
                </Grid>
            </Grid>
        );
    }
}