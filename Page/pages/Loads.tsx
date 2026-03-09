import React, {useRef, useMemo, useState, useEffect, Fragment} from "react";

import {useReactToPrint} from "react-to-print";

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

import Store from "./../services/Loads";

export namespace Loads {
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
            take: 0,
            page: 0,
            size: 0,
            done: 0,
            time: 0,
            item: 0,
            list: [],
            load: {
                firm: null
            },
            dump: {
                open: false,
                wait: false,
                done: false,
                fail: null,
                data: null
            },
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

        const node = useRef<HTMLDivElement>(null);
        
        const dump = useReactToPrint({contentRef: node, documentTitle: 'Informe'});

        useEffect(() => {
            Store.load(data.firm, data.page, data.find, Object.keys(data.pipe).filter((name) => ((typeof data.pipe[name]['data'] == 'number') || Boolean(data.pipe[name]['data']))).map((name) => {
                return `${name}: ${(data.pipe[name]['sign'])} ${(data.pipe[name]['data'] instanceof Array ? data.pipe[name]['data'].join(' ') : data.pipe[name]['data'])}`
            }, []).join(', '), null, data.sort, (done, data) => {
                if (done) {
                    setData((last) => ({
                        ...last,
                        wait: false,
                        list: data.list,
                        size: data.list.length,
                        take: data.list.length
                    }));
                } else {
                    //setNote({show: true, type: 'error', text: 'No se pudo cargar los registros.'});

                    setData((last) => ({...last, list: [], wait: false}));
                }
            });
        }, [data.page, data.sort, data.find, data.pipe, data.firm, data.time]);

        const type = useMemo(() => (
            {
                hint: 'Empresa',
                wait: Boolean(session.heap?.firm) ? false : true,
                list: data.load.firm,
                data: session.firm,
                none: 'No hay empresas disponibles.',
                load: (text: any) => {
                    setData((last) => ({
                        ...last,
                        load: {
                            ...last.load,
                            firm: ((session.type == 1) ? session.heap?.firm : session.heap?.firm?.filter((item: any) => ((item.lead == session.link))))?.map((next: any) => ({...next, text: next.name}))
                        }
                    }));
                },
                task: (pick: any) => {
                    setData((last) => ({
                        ...last,
                        firm: pick?.item,
                        wait: true
                    }));
                }
            }
        ), [session.heap, session.firm, data.load.firm]);
    
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
                            Tus informes
                        </Typography>
                        <Typography
                            color="text.secondary"
                            gutterBottom>
                            Consulta aquí tus informes mensual.
                        </Typography>
                    </Grid>
                    <Grid
                        display="flex"
                        flex={1}
                        item>
                        <Data
                            seek={'Id'}
                            name=""
                            type={([1, 2, 3].includes(session.type) ? type : null)}
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
                                                        <path d="M6.657 18C4.085 18 2 15.993 2 13.517s2.085-4.482 4.657-4.482c.393-1.762 1.794-3.2 3.675-3.773c1.88-.572 3.956-.193 5.444 1c1.488 1.19 2.162 3.007 1.77 4.769h.99c1.913 0 3.464 1.56 3.464 3.486s-1.551 3.487-3.465 3.487H6.657" />
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
                                                        <path d="M17 3.34a10 10 0 1 1-14.995 8.984L2 12l.005-.324A10 10 0 0 1 17 3.34M12 7a1 1 0 0 0-1 1v5.585l-2.293-2.292l-.094-.083a1 1 0 0 0-1.32 1.497l4 4q.04.04.094.083l.092.064l.098.052l.081.034l.113.034l.112.02L12 17l.115-.007l.114-.02l.142-.044l.113-.054l.111-.071a1 1 0 0 0 .112-.097l4-4l.083-.094a1 1 0 0 0-1.497-1.32L13 13.584V8l-.007-.117A1 1 0 0 0 12 7" />
                                                    </g>
                                                </SvgIcon>
                                            )}
                                            onClick={(event) => {
                                                Store.dump(data.firm, {date: item.date}, (done: boolean, data: any) => {
                                                    if (done) {
                                                        setData((last) => ({
                                                            ...last,
                                                            dump: {
                                                                ...last.dump,
                                                                wait: false,
                                                                done: true,
                                                                data: data
                                                            }
                                                        }));
                                                    } else {
                                                        setData((last) => ({
                                                            ...last,
                                                            dump: {
                                                                ...last.dump,
                                                                wait: false,
                                                                done: false,
                                                                fail: data?.text
                                                            }
                                                        }));
                                                    }
                                                });

                                                setData((last) => ({
                                                    ...last,
                                                    dump: {
                                                        done: false,
                                                        open: true,
                                                        wait: true,
                                                        fail: null,
                                                        data: null
                                                    }
                                                }));
                                            }}>
                                            Descargar
                                        </Button>
                                    )
                                }
                            ]}
                            data={[
                                {
                                    item: 'name',
                                    name: 'Informe',
                                    show: true,
                                    sort: true
                                },
                                {
                                    item: 'time',
                                    name: 'Consumo',
                                    edge: 'right',
                                    show: true,
                                    sort: true,
                                    size: 180,
                                    cast: (item: any) => (moment.utc((item.time * 1000)).format('HH:mm'))
                                },
                                {
                                    item: 'cost',
                                    name: 'Total',
                                    edge: 'right',
                                    show: true,
                                    sort: true,
                                    size: 260,
                                    cast: (item: any) => (`COP ${cost.format((item.cost ?? 0))}`)
                                }
                            ]}
                            none={{
                                text: 'Sin informes',
                                note: 'No se encontraron informes disponibles.'
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
                <Dialog
                    open={(data.dump.open)}
                    scroll="paper"
                    maxWidth="xl"
                    onClose={(event) => {
                        setData((last) => ({...last, dump: {...last.dump, open: false}}));
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
                            Informe
                        </Typography>
                        <Typography
                            color="text.secondary"
                            gutterBottom>
                            Informe de consumo.
                        </Typography>
                    </DialogTitle>
                    <DialogContent ref={node}>
                        {(data.dump.wait ? (
                            <Box
                                justifyContent="center"
                                alignItems="center"
                                display="flex"
                                height={96}>
                                <CircularProgress size={48} />
                            </Box>
                        ) : (data.dump.done ? (
                            <Box
                                sx={{'@media print': {
                                    '@page': {
                                        size: '16in 12in',
                                        margin: '2mm'
                                    }
                                 }}}
                                ref={node}
                                dangerouslySetInnerHTML={{__html: (data.dump.data ?? '')}} />
                        ) : (
                            <Alert severity="error">
                                {(data.dump.fail ?? 'No se pudo descargar el informe.')}
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
                                        disabled={data.dump.wait}
                                        onClick={(event) => {
                                            setData((last) => ({...last, dump: {...last.dump, open: false}}));
                                        }}>
                                        Cancelar
                                    </Button>
                                    <Button
                                        type="reset"
                                        color="primary"
                                        variant="contained"
                                        disabled={(data.dump.wait || (data.dump.done == false))}
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
            Store.load(item, data.page, data.find, Object.keys(data.pipe).filter((name) => ((typeof data.pipe[name]['data'] == 'number') || Boolean(data.pipe[name]['data']))).map((name) => {
                return `${name}: ${(data.pipe[name]['sign'])} ${(data.pipe[name]['data'] instanceof Array ? data.pipe[name]['data'].join(' ') : data.pipe[name]['data'])}`
            }, []).join(', '), 'Id, Ud, UserId, CustomerId, Task, Content, Creation, User[Id, Ud, Mail, Last, First, Image], Customer[Id, Ud, Name], Tasks[*]', data.sort, (done, data) => {
                if (done) {console.log('data',data)
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
                        menu={[
                            {
                                icon: ['M12 5v14m-7-7h14'],
                                type: 'push',
                                name: 'make',
                                hint: 'Consultar',
                                task: () => {
                                }
                            }
                        ]}
                        dash={[
                            {
                                icon: ['M13 5h8m-8 4h5m-5 6h8m-8 4h5M3 5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1zm0 10a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1z'],
                                hint: 'Detalles',
                                name: 'View',
                                task: (item) => {
                                    //navigate(`/servicios/${item.item}`);
                                }
                            }
                        ]}
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