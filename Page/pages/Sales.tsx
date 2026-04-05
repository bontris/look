import React, {useMemo, useState, useEffect} from "react";

import moment from "moment";

import {
    useParams,
    useNavigate
} from "react-router-dom";

import {
    Box,
    Card,
    Grid,
    Menu,
    Link,
    alpha,
    Stack,
    Paper,
    Alert,
    AppBar,
    Dialog,
    Drawer,
    Avatar,
    Button,
    Divider,
    Toolbar,
    SvgIcon,
    Backdrop,
    Collapse,
    Snackbar,
    MenuItem,
    ListItem,
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

import {BACK} from "../environment";

import {useApplication} from "../hooks/Application";

import {useSession} from "../hooks/Session";

import type {Null} from "../types/Null";

import type {Hash} from "../types/Hash";

import type {User} from "../types/User";

import {Data} from "../components/Data";

import {Form} from "../components/Form";

import Store from "../services/Sales";

export namespace Sales {
    export const List = ({task}: {task?: string}) => {
        const {
            setAlert,
            setTitle,
            setDialog
        } = useApplication();

        const navigate = useNavigate();

        const [take, setTake] = useState(16);

        const [page, setPage] = useState(1);

        const [data, setData] = useState({
            find: null as Null<string>,
            sort: {} as Hash<boolean>,
            play: false,
            wait: true,
            firm: null,
            dash: '',
            take: 16,
            page: 0,
            size: 0,
            done: 0,
            time: 0,
            item: 0,
            list: [],
            pipe: {
            } as Hash<{data: Null<any>, sign: '~' | '=' | '<' | '>' | '!'}>
        });

        const cost = new Intl.NumberFormat('es-CO', {
            maximumFractionDigits: 0,
            currency: 'COP',
            style: 'currency',
        });

        useEffect(() => {
            Store.load(data.take, data.page, data.find, Object.keys(data.pipe).filter((name) => ((typeof data.pipe[name]['data'] == 'number') || Boolean(data.pipe[name]['data']))).map((name) => {
                return `${name}: ${(data.pipe[name]['sign'])} ${(data.pipe[name]['data'] instanceof Array ? data.pipe[name]['data'].join(' ') : data.pipe[name]['data'])}`
            }, []).join(', '), null, data.sort, (done, data) => {
                if (done) {
                    setData((last) => ({...last, wait: false, page: data.page, list: data.data, size: data.size}));
                } else {
                    //setNote({show: true, type: 'error', text: 'No se pudo cargar los registros.'});

                    setData((last) => ({...last, list: [], wait: false}));
                }
            });
        }, [data.page, data.take, data.sort, data.find, data.pipe, data.time]);

        useEffect(() => {
            setTitle('Ventas');
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
                        Listado de Ventas
                    </Typography>
                    <Typography
                        color="text.secondary"
                        gutterBottom>
                        Módulo de manejo de ventas.
                    </Typography>
                </Grid>
                <Grid
                    display="flex"
                    flex={1}
                    item>
                    <Data
                        seek={'item'}
                        name=""
                        menu={[
                            {
                                type: 'push',
                                name: 'make',
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
                                                    <path d="M6.5 7.5a1 1 0 1 0 2 0a1 1 0 1 0-2 0" />
                                                    <path d="M3 6v5.172a2 2 0 0 0 .586 1.414l7.71 7.71a2.41 2.41 0 0 0 3.408 0l5.592-5.592a2.41 2.41 0 0 0 0-3.408l-7.71-7.71A2 2 0 0 0 11.172 3H6a3 3 0 0 0-3 3" />
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
                                            navigate(`/ventas/make`);
                                        }}>
                                        Agregar
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
                                            navigate(`/ventas/edit/${item.hash}`);
                                        }}>
                                        Detalles
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
                                                ¿Deseas eliminar el registro <b>{moment(item.date).format('DD/MM/YYYY')}</b>?
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
                                                        setData((last) => ({
                                                            ...last,
                                                            time: Date.now()
                                                        }));

                                                        setAlert('El registro fue eliminado.', 'success');
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
                                item: 'firm',
                                name: 'Empresa',
                                show: true,
                                sort: true
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
                                        item: 1,
                                        tint: '2979FF',
                                        text: 'Horas'
                                    }
                                ]
                            },
                            {
                                item: 'time',
                                name: 'Horas',
                                edge: 'center',
                                show: true,
                                sort: true,
                                size: 120,
                                cast: (item: any) => (moment.utc(((item.time * 3600) * 1000)).format('HH:mm'))
                            },
                            {
                                item: 'cost',
                                name: 'Coste',
                                edge: 'center',
                                show: true,
                                sort: true,
                                size: 180,
                                cast: (item: any) => (`COP ${cost.format((item.cost ?? 0))}`)
                            },
                            {
                                item: 'push',
                                edge: 'center',
                                name: 'Acción',
                                show: true,
                                sort: true,
                                size: 120,
                                pick: [
                                    {
                                        item: 1,
                                        tint: '4CAF50',
                                        text: 'Agregar'
                                    },
                                    {
                                        item: 0,
                                        tint: 'FF9800',
                                        text: 'Ignorar'
                                    }
                                ]
                            },
                            {
                                item: 'date',
                                name: 'Fecha',
                                edge: 'right',
                                show: true,
                                sort: true,
                                size: 120,
                                cast: (item: any) => (moment(item.date).format('DD/MM/YYYY'))
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
                            setData((last) => ({...last, page: page ?? 1, take: take ?? 16, sort: sort, find: find, pipe: pipe, wait: true}));
                        }} />
                </Grid>
            </Grid>
        );
    }

    export const Make = () => {
        const navigate = useNavigate();

        const {session} = useSession();

        const {
            setAlert,
            setTitle
        } = useApplication();

        const [data, setData] = useState<{wait: boolean, lock: boolean, fail: Hash<string>, heap: Hash<any>, form: Hash<any>}>({
            heap: {
                team: {
                    wait: false,
                    fail: false,
                    take: 0,
                    size: 0,
                    list: []
                }
            },
            wait: false,
            lock: false,
            form: {
                paid: null,
                push: null,
                type: null,
                cost: null,
                time: null,
                bind: null,
                code: null,
                note: null
            },
            fail: {}
        });

        const view:any = useMemo(() => (
            {
                data: [
                    {
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
                        bind: 'La opción es requerida.',
                        list: [
                            {item: 1, text: 'Horas'}
                        ]
                    },
                    {
                        type: 'list',
                        size: 'half',
                        name: 'bind',
                        text: 'Empresa',
                        bind: 'La opción es requerida.',
                        list: session.heap?.firm?.map((next: any) => ({item: next.item, text: next.name, hint: next.card})) ?? [],
                        live: (item: any, form: any) => {
                            setData((last) => ({...last, form: {...form}}));
                        }
                    },
                    {
                        type: 'menu',
                        size: 'half',
                        name: 'paid',
                        text: 'Estado',
                        bind: 'La opción es requerida.',
                        list: [
                            {item: 1, text: 'Pagada'},
                            {item: 0, text: 'Pendiente'}
                        ]
                    },
                    {
                        type: 'menu',
                        size: 'half',
                        name: 'push',
                        text: 'Acción',
                        bind: 'La opción es requerida.',
                        list: [
                            {item: 1, text: 'Agregar'},
                            {item: 0, text: 'Ignorar'}
                        ]
                    },
                    {
                        type: 'text',
                        size: 'half',
                        name: 'time',
                        text: 'Horas',
                        hint: 'Número de horas',
                        bind: 'El campo es requerido.'
                    },
                    {
                        type: 'text',
                        size: 'half',
                        name: 'cost',
                        text: 'Costo',
                        hint: 'Costo por hora',
                        bind: 'El campo es requerido.'
                    },
                    {
                        type: 'date',
                        size: 'half',
                        name: 'date',
                        text: 'Fecha',
                        hint: 'Fecha de venta',
                        bind: 'El campo es requerido.'
                    },
                    {
                        type: 'text',
                        size: 'half',
                        name: 'code',
                        text: 'Referencia',
                        hint: 'Código de referencia.'
                    },
                    {
                        type: 'area',
                        size: 'full',
                        name: 'note',
                        text: 'Nota'
                    }
                ]
            }
        ), [session.heap]);

        useEffect(() => {
            setTitle('Nuevo Anuncio');
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
                        Nueva Venta
                    </Typography>
                    <Typography
                        color="text.secondary"
                        gutterBottom>
                        Módulo de manejo de ventas.
                    </Typography>
                </Grid>
                <Grid
                    display="flex"
                    flex={1}
                    item>
                    <Form
                        view={view}
                        data={data.form}
                        fail={data.fail}
                        wait={data.wait}
                        lock={data.lock}
                        quit={{
                            text: 'Cancelar',
                            task: () => {
                                navigate('/ventas');
                            }
                        }}
                        save={{
                            text: 'Guardar',
                            task: (form: Hash<any>) => {
                                setData((last) => ({
                                    ...last,
                                    lock: true,
                                    form: form
                                }));

                                Store.make(form, (done: boolean, data: any) => {
                                    if (done) {
                                        navigate('/ventas');

                                        setAlert((data?.text ?? 'El registro fue creado correctamente.'), 'success');
                                    } else {
                                        setData((last) => ({...last, lock: false, fail: data?.list ?? {}}));

                                        setAlert((data?.text ?? 'El registro no pudo ser creado correctamente.'), 'error');
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
        const {item} = useParams();
    
        const [form, setForm] = useState(false);
    
        return (
            `Add view user: ${item}`
        );
    }

    export const Edit = () => {
        const navigate = useNavigate();

        const {session} = useSession();

        const {
            setAlert,
            setTitle
        } = useApplication();

        const [data, setData] = useState<{wait: boolean, lock: boolean, fail: Hash<string>, heap: Hash<any>, form: Hash<any>}>({
            heap: {
                team: {
                    wait: false,
                    fail: false,
                    take: 0,
                    size: 0,
                    list: []
                }
            },
            wait: false,
            lock: false,
            form: {},
            fail: {}
        });

        const [done, setDone] =  useState(false);

        const [fail, setFail] =  useState(false);

        const {item} = useParams();

        const view:any = useMemo(() => (
            {
                data: [
                    {
                        line: true,
                        name: 'Main',
                        text: 'Básica',
                        hint: 'Información básica'
                    },
                    {
                        type: 'text',
                        size: 'half',
                        name: 'time',
                        text: 'Horas',
                        hint: 'Número de horas',
                        bind: 'El campo es requerido.'
                    },
                    {
                        type: 'text',
                        size: 'half',
                        name: 'cost',
                        text: 'Costo',
                        hint: 'Costo por hora',
                        bind: 'El campo es requerido.'
                    },
                    {
                        type: 'date',
                        size: 'half',
                        name: 'date',
                        text: 'Fecha',
                        hint: 'Fecha de venta',
                        bind: 'El campo es requerido.'
                    },
                    {
                        type: 'text',
                        size: 'half',
                        name: 'code',
                        text: 'Referencia',
                        hint: 'Código de referencia.'
                    },
                    {
                        type: 'area',
                        size: 'full',
                        name: 'note',
                        text: 'Nota'
                    }
                ]
            }
        ), []);

        useEffect(() => {
            setTitle('Editar Venta');
        }, []);

        useEffect(() => {
            Store.find(`${item}`, '', (done, form) => {
                setTimeout(() => {
                    if (done) {
                        setDone(true);

                        setData((last) => ({
                            ...last,
                            form: {
                                ...form,
                                path: form.snap
                            }
                        }));
                    } else {
                        setFail(true);
                    }
                }, 300);
            });
        }, [item]);

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
                        Editar Venta
                    </Typography>
                    <Typography
                        color="text.secondary"
                        gutterBottom>
                        Módulo de manejo de ventas.
                    </Typography>
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
                                    No se pudo encontrar el registro.
                                </Typography>
                            </Box>
                        </Card>
                    ) : (done ? (
                        <Form
                            view={view}
                            data={data.form}
                            fail={data.fail}
                            wait={data.wait}
                            lock={data.lock}
                            quit={{
                                text: 'Cancelar',
                                task: () => {
                                    navigate('/ventas');
                                }
                            }}
                            save={{
                                text: 'Guardar',
                                task: (form: any) => {
                                    setData((last) => ({...last, lock: true}));

                                    Store.save(`${item}`, form, (done: boolean, data: any) => {
                                        if (done) {
                                            navigate('/ventas');

                                            setAlert((data?.text ?? 'El registro fue actualizada correctamente.'), 'success');
                                        } else {
                                            setData((last) => ({...last, lock: false, fail: data?.list ?? {}}));

                                            setAlert((data?.text ?? 'El registro no pudo ser actualizado correctamente.'), 'error');
                                        }
                                    });
                                }
                            }}
                        />
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
        );
    }
}