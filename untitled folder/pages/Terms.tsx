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

import type {Null} from "../types/Null";

import type {Hash} from "../types/Hash";

import {Data} from "../components/Data";

import {Form} from "../components/Form";

import Store from "../services/Terms";

export namespace Terms {
    export const List = ({task}: {task?: string}) => {
        const {
            setAlert,
            setTitle,
            setDialog
        } = useApplication();

        const navigate = useNavigate();

        const [take, setTake] = useState(16);

        const [page, setPage] = useState(1);

        const [data, setData] = useState<{
            pipe: Hash<{data: Null<any>, sign: '~' | '=' | '<' | '>' | '!'}>,
            list: Array<Hash<any>>,
            item: Null<Hash<any>>,
            find: Null<string>,
            form: Hash<any>,
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
            form: {
                bulk: {
                    wait: false,
                    open: false,
                    item: null,
                    text: null,
                    list: null,
                    data: {
                        post: null,
                        date: null,
                        file: null
                    }
                }
            },
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
            setTitle('Solicitudes');
        }, []);
    
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
                            Gacetas
                        </Typography>
                        <Typography
                            color="text.secondary"
                            gutterBottom>
                            Módulo de manejo de gacetas.
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
                                                        <path d="M4.929 4.929A10 10 0 1 1 19.07 19.07A10 10 0 0 1 4.93 4.93zM13 9a1 1 0 1 0-2 0v2H9a1 1 0 1 0 0 2h2v2a1 1 0 1 0 2 0v-2h2a1 1 0 1 0 0-2h-2z" />
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
                                                                post: null,
                                                                date: null,
                                                                file: null
                                                            }
                                                        }
                                                    }
                                                }));
                                            }}>
                                            Importar
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
                                            href={item.link}
                                            color="primary"
                                            target="_blank"
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
                                            )}>
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
                                                ¿Deseas eliminar el registro <b>{item.text} {item.last}</b>?
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
                                    item: 'text',
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
                                                    background: alpha(theme.palette.primary.main, 0.6)
                                                })}>
                                                {item.text[0]}
                                            </Avatar>
                                            <Stack
                                                sx={{overflow: 'hidden'}}
                                                direction="column">
                                                <Typography
                                                    color={(theme) => (theme.palette.text.primary)}
                                                    noWrap>
                                                    {item.text}
                                                </Typography>
                                                <Typography
                                                    color={(theme) => (theme.palette.text.secondary)}
                                                    noWrap>
                                                    <Link
                                                        href={item.link}
                                                        target="_blank">
                                                        {item.card ?? item.code}
                                                    </Link>
                                                </Typography>
                                            </Stack>
                                        </Stack>
                                    )
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
                                            tint: '1976D2',
                                            text: 'Publicada'
                                        },
                                        {
                                            item: 2,
                                            tint: '2E7D32',
                                            text: 'Registrada'
                                        },
                                        {
                                            item: 3,
                                            tint: 'F9B825',
                                            text: 'Bajo examen de fondo'
                                        },
                                        {
                                            item: 4,
                                            tint: 'D84315',
                                            text: 'Negada'
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
                                setData((last) => ({...last, page: page ?? last.page, take: take ?? last.take, sort: sort, find: find, pipe: pipe, wait: true}));
                            }} />
                    </Grid>
                </Grid>
                <Dialog
                    open={(data.form.bulk.open)}
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
                            Importar
                        </Typography>
                        <Typography
                            color="text.secondary"
                            gutterBottom>
                            Importar solicitudes
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
                                                type: 'text',
                                                size: 'full',
                                                name: 'post',
                                                text: 'Número',
                                                bind: 'El campo es requerido.',
                                                hint: 'Número de la gaceta.'
                                            },
                                            {
                                                type: 'date',
                                                size: 'full',
                                                name: 'date',
                                                text: 'Fecha',
                                                bind: 'El campo es requerido.',
                                                hint: 'Fecha de la gaceta.'
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
            </Fragment>
        );
    }

    export const View = () => {
        const {item} = useParams();
    
        const [form, setForm] = useState(false);
    
        return (
            `Add view user: ${item}`
        );
    }
}