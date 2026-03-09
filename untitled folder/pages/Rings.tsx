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

import Store from "../services/Rings";

export namespace Rings {
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

        useEffect(() => {console.log('sort',data.sort)
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
            setTitle('Clientes');
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
                        Listado de Notificaciones
                    </Typography>
                    <Typography
                        color="text.secondary"
                        gutterBottom>
                        Módulo de manejo de notificaciones.
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
                                                    <path d="M12.5 17H4a4 4 0 0 0 2-3v-3a7 7 0 0 1 4-6a2 2 0 1 1 4 0a7 7 0 0 1 4 6v1m-9 5v1a3 3 0 0 0 3.51 2.957M16 19h6m-3-3v6" />
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
                                            navigate(`/notificaciones/make`);
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
                                            navigate(`/notificaciones/edit/${item.hash}`);
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
                                            ¿Deseas eliminar el registro <b>{item.name}</b>?
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
                                item: 'name',
                                name: 'Notificacion',
                                show: true,
                                sort: true,
                                cast: (item: any) => (
                                    <Stack
                                        sx={{alignItems: 'center'}}
                                        spacing={1}
                                        direction="row">
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
                                                {item.note}
                                            </Typography>
                                        </Stack>
                                    </Stack>
                                )
                            },
                            {
                                item: 'skip',
                                name: 'Usuario',
                                show: true,
                                sort: true,
                                cast: (item: any) => (
                                    <Stack
                                        sx={{alignItems: 'center'}}
                                        spacing={1}
                                        direction="row">
                                        <Stack
                                            sx={{overflow: 'hidden'}}
                                            direction="column">
                                            <Typography
                                                color={(theme) => (theme.palette.text.primary)}
                                                noWrap>
                                                {item.skip}
                                            </Typography>
                                            <Typography
                                                color={(theme) => (theme.palette.text.secondary)}
                                                noWrap>
                                                {item.mail}
                                            </Typography>
                                        </Stack>
                                    </Stack>
                                )
                            },
                            {
                                item: 'type',
                                edge: 'left',
                                name: 'Tipo',
                                show: true,
                                sort: true,
                                size: 180,
                                pick: [
                                    {
                                        item: 0,
                                        tint: '9E9E9E',
                                        text: 'N/A'
                                    },
                                    {
                                        item: 1,
                                        tint: '2196F3',
                                        text: 'Nota'
                                    },
                                    {
                                        item: 2,
                                        tint: '2E7D32',
                                        text: 'Éxito'
                                    },
                                    {
                                        item: 3,
                                        tint: 'FFD600',
                                        text: 'Precaución'
                                    },
                                    {
                                        item: 4,
                                        tint: 'D32F2F',
                                        text: 'Inconveniente'
                                    }
                                ]
                            },
                            {
                                item: 'made',
                                name: 'Creación',
                                edge: 'right',
                                show: true,
                                sort: true,
                                size: 180,
                                cast: (item: any) => (moment(item.createdAt).format('MM/DD/YY LT'))
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
                skip: null,
                type: null,
                link: null,
                name: null,
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
                            {item: 1, text: 'Nota'},
                            {item: 2, text: 'Éxito'},
                            {item: 3, text: 'Precaución'},
                            {item: 4, text: 'Inconveniente'}
                        ]
                    },
                    {
                        type: 'list',
                        size: 'half',
                        name: 'skip',
                        text: 'Usuario',
                        bind: 'La opción es requerida.',
                        list: session.heap?.skip?.map((next: any) => ({item: next.item, text: `${next.name} ${next.last}`, hint: next.mail})) ?? [],
                        live: (item: any, form: any) => {
                            setData((last) => ({...last, form: {...form}}));
                        }
                    },
                    {
                        type: 'text',
                        size: 'full',
                        name: 'link',
                        text: 'Enlace',
                        hint: 'Enlace de la notificación'
                    },
                    {
                        type: 'text',
                        size: 'full',
                        name: 'name',
                        text: 'Título',
                        hint: 'Téitulo de la notificación',
                        bind: 'El campo es requerido.'
                    },
                    {
                        type: 'text',
                        size: 'full',
                        name: 'note',
                        text: 'Contenido',
                        hint: 'Contenido de la nitificación',
                        bind: 'El campo es requerido.'
                    }
                ]
            }
        ), [session.heap, data.heap]);

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
                        Nueva Notificación
                    </Typography>
                    <Typography
                        color="text.secondary"
                        gutterBottom>
                        Módulo de manejo de notificaciones.
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
                                navigate('/notificaciones');
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
                                        navigate('/notificaciones');

                                        setAlert((data?.text ?? 'La notificación fue creada correctamente.'), 'success');
                                    } else {
                                        setData((last) => ({...last, lock: false, fail: data?.list ?? {}}));

                                        setAlert((data?.text ?? 'La notificación no pudo ser creada correctamente.'), 'error');
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
                        name: 'Snap',
                        text: 'Portada',
                        hint: 'Imágen de portada'
                    },
                    {
                        type: 'menu',
                        size: 'half',
                        name: 'type',
                        text: 'Tipo',
                        bind: 'La opción es requerida.',
                        list: [
                            {item: 1, text: 'Nota'},
                            {item: 2, text: 'Éxito'},
                            {item: 3, text: 'Precaución'},
                            {item: 4, text: 'Inconveniente'}
                        ]
                    },
                    {
                        type: 'list',
                        size: 'half',
                        name: 'skip',
                        text: 'Usuario',
                        bind: 'La opción es requerida.',
                        list: session.heap?.skip?.map((next: any) => ({item: next.item, text: `${next.name} ${next.last}`, hint: next.mail})) ?? [],
                        live: (item: any, form: any) => {
                            setData((last) => ({...last, form: {...form}}));
                        }
                    },
                    {
                        type: 'text',
                        size: 'full',
                        name: 'link',
                        text: 'Enlace',
                        hint: 'Enlace de la notificación'
                    },
                    {
                        type: 'text',
                        size: 'full',
                        name: 'name',
                        text: 'Título',
                        hint: 'Téitulo de la notificación',
                        bind: 'El campo es requerido.'
                    },
                    {
                        type: 'text',
                        size: 'full',
                        name: 'note',
                        text: 'Contenido',
                        hint: 'Contenido de la nitificación',
                        bind: 'El campo es requerido.'
                    }
                ]
            }
        ), [session.heap, data.heap]);

        useEffect(() => {
            setTitle('Editar Notificación');
        }, []);

        useEffect(() => {
            Store.find(`${item}`, '', (done, form) => {
                setTimeout(() => {
                    if (done) {
                        setDone(true);

                        setData((last) => ({
                            ...last,
                            form: form
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
                        Editar Notificación
                    </Typography>
                    <Typography
                        color="text.secondary"
                        gutterBottom>
                        Módulo de manejo de notificaciones.
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
                                    No encontrada
                                </Typography>
                                <Typography
                                    color="text.secondary"
                                    gutterBottom>
                                    No se pudo encontrar la notificación.
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
                                    navigate('/notificaciones');
                                }
                            }}
                            save={{
                                text: 'Guardar',
                                task: (form: any) => {
                                    setData((last) => ({...last, lock: true}));

                                    Store.save(`${item}`, {...form, snap: form.snap instanceof File ? form.snap : null}, (done: boolean, data: any) => {
                                        if (done) {
                                            navigate('/notificaciones');

                                            setAlert((data?.text ?? 'La notificación fue actualizada correctamente.'), 'success');
                                        } else {
                                            setData((last) => ({...last, lock: false, fail: data?.list ?? {}}));

                                            setAlert((data?.text ?? 'La notificación no pudo ser actualizado correctamente.'), 'error');
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