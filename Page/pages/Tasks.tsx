import React, {useMemo, useState, useEffect, Fragment} from "react";

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
    TableRow,
    TableCell,
    TableHead,
    TableBody,
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

import {useApplication} from "../hooks/Application";

import {useSession} from "./../hooks/Session";

import type {Null} from "../types/Null";

import type {Hash} from "../types/Hash";

import {Data} from "./../components/Data";

import {Form} from "./../components/Form";

import Store from "./../services/Tasks";

export namespace Tasks {
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

        const [data, setData] = useState({
            find: null as Null<string>,
            sort: {} as Hash<boolean>,
            play: false,
            wait: true,
            firm: null,
            hash: null,
            dash: '',
            take: 16,
            page: 0,
            size: 0,
            done: 0,
            time: 0,
            item: 0,
            list: [],
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
                post: {
                    wait: false,
                    open: false,
                    data: {} as Hash<any>
                }
            },
            pipe: {
            } as Hash<{data: Null<any>, sign: '~' | '=' | '<' | '>' | '!'}>
        });

        useEffect(() => {
            Store.load(null, data.take, data.page, data.find, Object.keys(data.pipe).filter((name) => ((typeof data.pipe[name]['data'] == 'number') || Boolean(data.pipe[name]['data']))).map((name) => {
                return `${name}: ${(data.pipe[name]['sign'])} ${(data.pipe[name]['data'] instanceof Array ? data.pipe[name]['data'].join(' ') : data.pipe[name]['data'])}`
            }, []).join(', '), null, data.sort, (done, data) => {
                if (done) {console.log('data', data)
                    setData((last) => ({...last, wait: false, page: data.page, list: data.list, size: data.size, take: data.take}));
                } else {
                    //setNote({show: true, type: 'error', text: 'No se pudo cargar los registros.'});

                    setData((last) => ({...last, list: [], wait: false}));
                }
            });
        }, [data.page, data.sort, data.find, data.pipe, data.time]);

        const team = useMemo(() => (
            (session.heap?.head?.reduce((hash: Hash<any>, data: any) => {
                hash[data.item] = data;

                return hash;
            }, {}) ?? {})
        ), [session.heap]);

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
                            Tus servicios
                        </Typography>
                        <Typography
                            color="text.secondary"
                            gutterBottom>
                            Consulta aquí el listado de tus servicios y realiza solicitud de nuevos servicios.
                        </Typography>
                    </Grid>
                    <Grid
                        display="flex"
                        flex={1}
                        item>
                        <Data
                            seek={'Id'}
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
                                                        <path d="M12.003 21c-.732.001-1.465-.438-1.678-1.317a1.724 1.724 0 0 0-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 0 0-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 0 0 1.066-2.573c-.94-1.543.826-3.31 2.37-2.37c1 .608 2.296.07 2.572-1.065c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c.886.215 1.325.957 1.318 1.694" />
                                                        <path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0-6 0m8.001 7a2 2 0 1 0 4 0a2 2 0 1 0-4 0m2-3.5V17m0 4v1.5m3.031-5.25l-1.299.75m-3.463 2l-1.3.75m0-3.5l1.3.75m3.463 2l1.3.75" />
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
                                                navigate('/servicios/make');
                                            }}>
                                            Solicitar Servicio
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
                                                navigate(`/servicios/${item.item}`);
                                            }}>
                                            Detalles
                                        </Button>
                                    )
                                }
                            ]}
                            data={[
                                ...([1, 2, 3].includes(session.type) ? [{
                                    item: 'firm',
                                    name: 'Empresa',
                                    show: true,
                                    sort: true
                                }] : []),
                                {
                                    item: 'item',
                                    name: 'Id',
                                    size: 120,
                                    show: true,
                                    sort: true
                                },
                                {
                                    item: 'name',
                                    name: 'Servicio',
                                    show: true,
                                    sort: true,
                                    cast: (item: any) => (
                                        <Stack
                                            sx={{alignItems: 'center'}}
                                            spacing={1}
                                            direction="row">
                                            <Avatar
                                                src={(team[item.head]?.icon ?? '')}
                                                sx={(theme) => ({
                                                    width: 48,
                                                    height: 48,
                                                    background: lighten(theme.palette.primary.main, 0.6)
                                                })}>
                                                {`${(team[item.head]?.name?.[0] ?? '')}${(team[item.head]?.last?.[0] ?? '')}`}
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
                                                    <Link href="#">{team[item.head]?.name} {team[item.head]?.last}</Link>
                                                    {(team[item.head]?.spot && `, ${team[item.head]?.spot}`)}
                                                </Typography>
                                            </Stack>
                                        </Stack>
                                    )
                                },
                                {
                                    item: 'made',
                                    name: 'Creación',
                                    edge: 'right',
                                    show: true,
                                    sort: true,
                                    size: 180,
                                    cast: (item: any) => (moment(item.made).format('DD/MM/YY LT'))
                                }
                            ]}
                            find={{
                                hint: 'Buscar',
                                text: data.find
                            }}
                            none={{
                                text: 'No Resources',
                                note: 'No available resources found.'
                            }}
                            take={{
                                pick: data.take ?? 16,
                                list: [16, 32, 64]
                            }}
                            wait={data.wait}
                            list={data.list}
                            size={data.size}
                            page={data.page}
                            load={(page, take, find, pipe, sort) => {
                                setData((last) => ({...last, page: page ?? data.page, sort: sort, find: find, pipe: pipe, wait: true}));
                            }} />
                    </Grid>
                </Grid>
                <Dialog
                    open={(data.form.post.open)}
                    onClose={(event) => {
                        setData((last) => ({...last, form: {...last.form, post: {...last.form.post, open: false}}}));
                    }}>
                    <DialogTitle
                        sx={{px: 3, pt: 4, pb: 0}}
                        alignItems="center"
                        justifyContent="center">
                        <Typography
                            color="text.primary"
                            variant="h5"
                            gutterBottom>
                            Solicitar
                        </Typography>
                        <Typography
                            color="text.secondary"
                            gutterBottom>
                            Solicitar nuevo servicio.
                        </Typography>
                    </DialogTitle>
                    <DialogContent sx={{pb: 0, px: 0}}>
                        <Form
                            data={data.form.post.data}
                            wait={data.form.post.wait}
                            lock={data.form.post.wait}
                            flat={true}
                            view={{
                                data: [
                                    {
                                        type: 'text',
                                        size: 'full',
                                        name: 'hint',
                                        text: 'Asunto',
                                        bind: 'El asunto es requerido.'
                                    },
                                    {
                                        type: 'area',
                                        size: 'full',
                                        name: 'note',
                                        text: 'Mensaje',
                                        bind: 'El mensaje es requerido.'
                                    },
                                    {
                                        type: 'file',
                                        size: 'full',
                                        name: 'file',
                                        kind: ['application/pdf'],
                                        text: 'Documento',
                                        hint: 'Seleccione el documento a firmar',
                                        bind: 'El documento es requerido.'
                                    },
                                    {
                                        line: true,
                                        name: 'List',
                                        text: 'Firmantes',
                                        hint: 'Personas que deben firmar el documento.'
                                    },
                                    {
                                        tile: true,
                                        size: 'full',
                                        name: 'list',
                                        text: 'Agergar firmante',
                                        form: {
                                            size: 1,
                                            high: 8,
                                            list: [
                                                {
                                                    type: 'text',
                                                    name: 'name',
                                                    text: 'Nombre',
                                                    size: 'half',
                                                    hint: 'Nombre completo',
                                                    bind: 'El nombre es requerido.'
                                                },
                                                {
                                                    type: 'text',
                                                    name: 'mail',
                                                    text: 'Correo',
                                                    size: 'half',
                                                    hint: 'Correo electrónico',
                                                    bind: 'El correo es requerida.'
                                                }
                                            ],
                                            push: (next: number) => ({name: null, mail: null})
                                        }
                                    },
                                ]
                            } as any}
                            quit={{
                                task: (step, exit) => {
                                    setData((last) => ({...last, form: {...last.form, post: {...last.form.post, open: false}}}));
                                }
                            }}
                            save={{
                                text: 'Solicitar',
                                task: (form: any, step?: number, save?: boolean) => {
                                    Store.post(form, (done: boolean, data: any) => {
                                        setData((last) => ({...last, time: (new Date).getTime(), form: {...last.form, post: {...last.form.post, open: false}}}));

                                        if (done) {
                                            setAlert((data?.text ?? 'La solicitud fue enviada con correctamente.'), 'success');
                                        } else {
                                            setAlert((data?.text ?? 'La solicitud no pudo ser enviada con correctamente.'), 'error');
                                        }
                                    });

                                    setData((last) => ({...last, form: {...last.form, post: {...last.form.post, wait: true}}}));
                                }
                            }} />
                    </DialogContent>
                </Dialog>
            </Fragment>
        );
    }

    export const View = () => {
        const {
            item
        } = useParams();
    
        const {
            setAlert,
            setTitle,
            setDialog
        } = useApplication();

        const [data, setData] = useState<{
            pipe: Hash<{data: Null<any>, sign: '~' | '=' | '<' | '>' | '!'}>,
            sort: Hash<boolean>,
            find: Null<string>,
            list: Array<any>,
            wait: boolean,
            lock: boolean,
            done: boolean,
            fail: boolean,
            time: number,
            take: number,
            page: number,
            size: number,
            pick: any
        }>({
            wait: false,
            lock: false,
            done: false,
            fail: false,
            pick: null,
            find: null,
            time: 0,
            take: 0,
            page: 0,
            size: 0,
            pipe: {},
            sort: {},
            list: []
        });

        const [zoom, setZoom] = useState(16);

        useEffect(() => {
            Store.find(`${item}`, (done, data) => {
                setTimeout(() => {
                    if (done) {
                        setData((last) => ({
                            ...last,
                            done: true,
                            pick: data
                        }));
                    } else {
                        setData((last) => (
                            {...last, fail: true}
                        ));
                    }
                }, 300);
            });

            setTitle('Firma');
        }, [item]);

        useEffect(() => {
            Store.load(item, data.take, data.page, data.find, Object.keys(data.pipe).filter((name) => ((typeof data.pipe[name]['data'] == 'number') || Boolean(data.pipe[name]['data']))).map((name) => {
                return `${name}: ${(data.pipe[name]['sign'])} ${(data.pipe[name]['data'] instanceof Array ? data.pipe[name]['data'].join(' ') : data.pipe[name]['data'])}`
            }, []).join(', '), null, data.sort, (done, data) => {
                if (done) {
                    setData((last) => ({...last, wait: false, page: data.page, list: data.list, size: data.size, take: data.take}));
                } else {
                    //setNote({show: true, type: 'error', text: 'No se pudo cargar los registros.'});

                    setData((last) => ({...last, list: [], wait: false}));
                }
            });
        }, [data.page, data.sort, data.find, data.pipe, data.time, item]);
    
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
                        Detalles
                    </Typography>
                    {(data.done ? (
                        <Typography
                            color="text.secondary"
                            gutterBottom>
                            Servicio "{data.pick.name}"
                        </Typography>
                    ) : (
                        <Typography
                            color="text.secondary"
                            gutterBottom>
                            Detalles servicio
                        </Typography>
                    ))}
                </Grid>
                <Grid
                    display="flex"
                    flex={1}
                    item>
                    {(data.done ? (
                        <Data
                            seek={'Id'}
                            name=""
                            data={[
                                {
                                    item: 'item',
                                    name: 'Id',
                                    size: 120,
                                    show: true,
                                    sort: true
                                },
                                {
                                    item: 'name',
                                    name: 'Tarea',
                                    show: true,
                                    sort: true,
                                    cast: (item: any) => (
                                        <Stack
                                            sx={{alignItems: 'center'}}
                                            spacing={1}
                                            direction="row">
                                            <Avatar
                                                src={item.lead.icon}
                                                sx={(theme) => ({
                                                    width: 48,
                                                    height: 48,
                                                    background: lighten(theme.palette.primary.main, 0.6)
                                                })}>
                                                {item.lead.name[0]}
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
                                                    <Link href="#">{item.lead.name}</Link>
                                                    {(item.lead.role && `, ${item.lead.role}`)}
                                                </Typography>
                                            </Stack>
                                        </Stack>
                                    )
                                },
                                {
                                    item: 'time',
                                    name: 'Consumo',
                                    edge: 'center',
                                    size: 180,
                                    show: true,
                                    sort: true,
                                    cast: (item) => (`${(item.time / 3600).toFixed(1)} hrs`)
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
                                            item: 2,
                                            tint: 'FFD600',
                                            text: 'Pendiente'
                                        },
                                        {
                                            item: 3,
                                            tint: '0277BD',
                                            text: 'En curso'
                                        },
                                        {
                                            item: 4,
                                            tint: 'FF8F00',
                                            text: 'Pendiente de revisión'
                                        },
                                        {
                                            item: 5,
                                            tint: '2E7D32',
                                            text: 'Completado'
                                        },
                                        {
                                            item: 6,
                                            tint: '455A64',
                                            text: 'Diferido'
                                        }
                                    ]
                                },
                                {
                                    item: 'date',
                                    name: 'Creación',
                                    edge: 'right',
                                    show: true,
                                    sort: true,
                                    size: 180,
                                    cast: (item: any) => (moment(item.date).format('DD/MM/YY LT'))
                                }
                            ]}
                            find={{
                                hint: 'Buscar',
                                text: data.find
                            }}
                            none={{
                                text: 'No Resources',
                                note: 'No available resources found.'
                            }}
                            take={{
                                pick: data.take ?? 16,
                                list: [16, 32, 64]
                            }}
                            wait={data.wait}
                            list={data.list}
                            size={data.size}
                            page={data.page}
                            load={(page, take, find, pipe, sort) => {
                                setData((last) => ({...last, page: page ?? data.page, sort: sort, find: find, pipe: pipe, wait: true}));
                            }} />
                    ) : ((data.fail ? (
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
                                    No se pudo encontrar el objeto.
                                </Typography>
                            </Box>
                        </Card>
                    ) : (
                        <Box
                            sx={{mt: 4, mb: 2}}
                            display="flex"
                            alignItems="center"
                            justifyContent="center">
                            <CircularProgress size={48} />
                        </Box>
                    ))))}
                </Grid>
            </Grid>
        );
    }

    export const Make = () => {
        const [form, setForm] = useState<{wait: boolean, lock: boolean, fail: Hash<string>, data: Hash<any>}>({
            data: {} as Hash<any>,
            wait: false,
            lock: false,
            fail: {}
        });

        const view: any = {
            data: [
                {
                    step: 0,
                    line: true,
                    name: 'Main',
                    text: 'Básica',
                    hint: 'Información básica'
                },
                {
                    type: 'text',
                    size: 'half',
                    name: 'name',
                    text: 'Nombre',
                    hint: 'Nombre del servicio',
                    bind: 'El campo es requerido.'
                },
                {
                    type: 'file',
                    size: 'half',
                    name: 'file',
                    kind: ['application/pdf'],
                    text: 'Documento',
                    hint: 'Documento relacionado con el servicio'
                },
                {
                    type: 'area',
                    size: 'full',
                    name: 'note',
                    text: 'Descripción',
                    hint: 'Descripción del servicio',
                    bind: 'El campo es requerido.'
                }
            ]
        };

        const navigate = useNavigate();

        const {setAlert, setTitle} = useApplication();

        useEffect(() => {
            setTitle('Solicitar servicio');
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
                        Solicitar servicio
                    </Typography>
                    <Typography
                        color="text.secondary"
                        gutterBottom>
                        Módulo de manejo de servicios
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
                                navigate('/servicios');
                            }
                        }}
                        save={{
                            text: 'Guardar',
                            task: async (data: Hash<any>) => {
                                setForm((last) => ({...last, lock: true, data: data}));
                                
                                Store.post(data, (done: boolean, data: any) => {
                                    if (done) {
                                        navigate('/servicios');

                                        setAlert((data?.text ?? 'El servicio fue solicitado correctamente.'), 'success');
                                    } else {
                                        setForm((last) => ({...last, lock: false, fail: data?.form ?? {}}));

                                        setAlert((data?.text ?? 'El servicio no pudo ser solicitado correctamente.'), 'error');
                                    }
                                });
                            }
                        }}
                    />
                </Grid>
            </Grid>
        );
    }
}