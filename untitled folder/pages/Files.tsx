import React, {useState, useEffect, Fragment} from "react";

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

import {BACK} from "./../environment";

import {useApplication} from "../hooks/Application";

import {useSession} from "./../hooks/Session";

import type {Null} from "../types/Null";

import type {Hash} from "../types/Hash";

import {Data} from "./../components/Data";

import {Form} from "./../components/Form";

import Store from "./../services/Files";

export namespace Files {
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
            pipe: {
            } as Hash<{data: Null<any>, sign: '~' | '=' | '<' | '>' | '!'}>
        });

        const cost = new Intl.NumberFormat('es-CO', {
            maximumFractionDigits: 0,
            currency: 'COP',
            style: 'currency',
        });

        useEffect(() => {
            Store.load(null, data.page, data.find, Object.keys(data.pipe).filter((name) => ((typeof data.pipe[name]['data'] == 'number') || Boolean(data.pipe[name]['data']))).map((name) => {
                return `${name}: ${(data.pipe[name]['sign'])} ${(data.pipe[name]['data'] instanceof Array ? data.pipe[name]['data'].join(' ') : data.pipe[name]['data'])}`
            }, []).join(', '), null, data.sort, (done, list) => {
                if (done) {
                    setData((last) => ({...last, wait: false, list: list, size: list.length}));
                } else {
                    //setNote({show: true, type: 'error', text: 'No se pudo cargar los registros.'});

                    setData((last) => ({...last, list: [], wait: false}));
                }
            });
        }, [data.page, data.sort, data.find, data.pipe, data.time]);
    
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
                            Dataroom legal
                        </Typography>
                        <Typography
                            color="text.secondary"
                            gutterBottom>
                            Consulta aquí los documentos legales de tu empresa elaborados y revisados por Taller A.
                        </Typography>
                    </Grid>
                    <Grid
                        display="flex"
                        flex={1}
                        item>
                        <Data
                            seek={'Id'}
                            name=""
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
                                                        <path d={((item.type == 'application/vnd.google-apps.folder') ? 'm5 19l2.757-7.351A1 1 0 0 1 8.693 11H21a1 1 0 0 1 .986 1.164l-.996 5.211A2 2 0 0 1 19.026 19za2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h4l3 3h7a2 2 0 0 1 2 2v2' : 'M6.657 18C4.085 18 2 15.993 2 13.517s2.085-4.482 4.657-4.482c.393-1.762 1.794-3.2 3.675-3.773c1.88-.572 3.956-.193 5.444 1c1.488 1.19 2.162 3.007 1.77 4.769h.99c1.913 0 3.464 1.56 3.464 3.486s-1.551 3.487-3.465 3.487H6.657')} />
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
                                                        <path d="m12 2l.324.005a10 10 0 1 1-.648 0zm.613 5.21a1 1 0 0 0-1.32 1.497L13.584 11H8l-.117.007A1 1 0 0 0 8 13h5.584l-2.291 2.293l-.083.094a1 1 0 0 0 1.497 1.32l4-4l.073-.082l.064-.089l.062-.113l.044-.11l.03-.112l.017-.126L17 12l-.007-.118l-.029-.148l-.035-.105l-.054-.113l-.071-.111a1 1 0 0 0-.097-.112l-4-4" />
                                                    </g>
                                                </SvgIcon>
                                            )}
                                            onClick={(event) => {
                                                if ((item.type == 'application/vnd.google-apps.folder')) {
                                                    navigate(`/documentos/${item.item}`);
                                                } else {
                                                    window.open(`${BACK}/loads/save?date=${item.date}`, '_blank');
                                                }
                                            }}>
                                            {((item.type == 'application/vnd.google-apps.folder') ? 'Explorar' : 'Descargar')}
                                        </Button>
                                    )
                                }
                            ]}
                            data={[
                                {
                                    item: 'name',
                                    name: 'Nombre',
                                    show: true,
                                    sort: true
                                }
                            ]}
                            find={{
                                hint: 'Buscar',
                                text: data.find
                            }}
                            none={{
                                text: 'Sin documentos',
                                note: 'No encontraron documentos disponibles.'
                            }}
                            take={{
                                pick: data.take
                            }}
                            wait={data.wait}
                            list={data.list}
                            size={data.size}
                            page={data.page}
                            load={(page, take, find, pipe, sort) => {
                                setData((last) => ({...last, page: page ?? 1, sort: sort, find: find, pipe: pipe, wait: true}));
                            }} />
                    </Grid>
                </Grid>
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

        const navigate = useNavigate();

        const [data, setData] = useState<{
            pipe: Hash<{data: Null<any>, sign: '~' | '=' | '<' | '>' | '!'}>,
            sort: Hash<boolean>,
            find: Null<string>,
            name: Null<string>,
            list: Array<any>,
            wait: boolean,
            lock: boolean,
            done: boolean,
            fail: boolean,
            time: number,
            take: number,
            page: number,
            size: number
        }>({
            wait: false,
            lock: false,
            done: false,
            fail: false,
            name: null,
            find: null,
            time: 0,
            take: 16,
            page: 0,
            size: 0,
            pipe: {},
            sort: {},
            list: []
        });

        const [zoom, setZoom] = useState(16);

        useEffect(() => {
            Store.open(`${item}`, (done, data) => {
                setTimeout(() => {
                    if (done) {console.log('open',data)
                        setData((last) => ({
                            ...last,
                            done: true,
                            name: data.name,
                            list: data.list,
                            size: data.list.length
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
                            {data.name}
                        </Typography>
                    ) : (
                        <Typography
                            color="text.secondary"
                            gutterBottom>
                            Listado de Documentos
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
                                                    <path d={((item.type == 'application/vnd.google-apps.folder') ? 'm5 19l2.757-7.351A1 1 0 0 1 8.693 11H21a1 1 0 0 1 .986 1.164l-.996 5.211A2 2 0 0 1 19.026 19za2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h4l3 3h7a2 2 0 0 1 2 2v2' : 'M6.657 18C4.085 18 2 15.993 2 13.517s2.085-4.482 4.657-4.482c.393-1.762 1.794-3.2 3.675-3.773c1.88-.572 3.956-.193 5.444 1c1.488 1.19 2.162 3.007 1.77 4.769h.99c1.913 0 3.464 1.56 3.464 3.486s-1.551 3.487-3.465 3.487H6.657')} />
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
                                                    <path d="m12 2l.324.005a10 10 0 1 1-.648 0zm.613 5.21a1 1 0 0 0-1.32 1.497L13.584 11H8l-.117.007A1 1 0 0 0 8 13h5.584l-2.291 2.293l-.083.094a1 1 0 0 0 1.497 1.32l4-4l.073-.082l.064-.089l.062-.113l.044-.11l.03-.112l.017-.126L17 12l-.007-.118l-.029-.148l-.035-.105l-.054-.113l-.071-.111a1 1 0 0 0-.097-.112l-4-4" />
                                                </g>
                                            </SvgIcon>
                                        )}
                                        onClick={(event) => {
                                            if ((item.type == 'application/vnd.google-apps.folder')) {
                                                navigate(`/documentos/${item.item}`);
                                            } else {
                                                Store.save(item.item, (done, file) => {
                                                    if (done) {
                                                        window.open(URL.createObjectURL(file), '_blank');
                                                    }
                                                });
                                            }
                                        }}>
                                        {((item.type == 'application/vnd.google-apps.folder') ? 'Explorar' : 'Descargar')}
                                    </Button>
                                )
                            }
                        ]}
                        data={[
                            {
                                item: 'name',
                                name: 'Nombre',
                                show: true,
                                sort: true
                            }
                        ]}
                        find={{
                            hint: 'Buscar',
                            text: data.find
                        }}
                        none={{
                            text: 'Sin documentos',
                            note: 'No encontraron documentos disponibles.'
                        }}
                        take={{
                            pick: data.take
                        }}
                        wait={data.wait}
                        list={data.list}
                        size={data.size}
                        page={data.page}
                        load={(page, take, find, pipe, sort) => {
                            setData((last) => ({...last, page: page ?? 1, sort: sort, find: find, pipe: pipe, wait: true}));
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
                            justifyContent="center"
                            alignItems="center"
                            display="flex"
                            flex={1}>
                            <CircularProgress size={48} />
                        </Box>
                    ))))}
                </Grid>
            </Grid>
        );
    }
}