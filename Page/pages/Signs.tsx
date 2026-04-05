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
    Stack,
    Paper,
    Alert,
    Table,
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
    Chip,
    CircularProgress
} from "@mui/material";

import {useApplication} from "../hooks/Application";

import AssignmentIcon from "@mui/icons-material/Assignment";

import InstagramIcon from "@mui/icons-material/Instagram";

import PersonIcon from "@mui/icons-material/Person";

import NoneIcon from "@mui/icons-material/Inbox";

import type {Null} from "../types/Null";

import type {Hash} from "../types/Hash";

import type {User} from "../types/User";

import {Data} from "./../components/Data";

import {Form} from "./../components/Form";

import {useSession} from "./../hooks/Session";

import Store from "./../services/Signs";

export namespace Signs {
    export const List = ({task}: {task?: string}) => {
        const {
            setAlert,
            setTitle,
            setDialog
        } = useApplication();

        const {type} = useParams();

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
            Store.load(({'documentos': 1} as const)[(type as string)] ?? 1, data.take, data.page, data.find, Object.keys(data.pipe).filter((name) => ((typeof data.pipe[name]['data'] == 'number') || Boolean(data.pipe[name]['data']))).map((name) => {
                return `${name}: ${(data.pipe[name]['sign'])} ${(data.pipe[name]['data'] instanceof Array ? data.pipe[name]['data'].join(' ') : data.pipe[name]['data'])}`
            }, []).join(', '), null, data.sort, (done, data) => {
                if (done) {
                    setData((last) => ({...last, wait: false, page: data.page, list: data.data, size: data.size, take: data.take}));
                } else {
                    //setNote({show: true, type: 'error', text: 'No se pudo cargar los registros.'});

                    setData((last) => ({...last, list: [], wait: false}));
                }
            });
        }, [data.page, data.take, data.sort, data.find, data.pipe, data.time, type]);
    
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
                            {({'documentos': 'Listado de documentos firmados'} as const)[(type as string)]}
                        </Typography>
                        <Typography
                            color="text.secondary"
                            gutterBottom>
                            {({'documentos': 'Consulta aquí los documentos que haz firmado.'} as const)[(type as string)]}
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
                                                navigate(`/firmas/${type}/${item.hash}`);
                                            }}>
                                            Detalles
                                        </Button>
                                    )
                                },
                                {
                                    icon: ['M14 3v4a1 1 0 0 0 1 1h4M17 21H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7l5 5v11a2 2 0 0 1-2 2zM12 17v-6M9.5 14.5L12 17l2.5-2.5'],
                                    hint: 'Documento',
                                    name: 'Link',
                                    view: (item: any, lock: boolean) => (
                                        <Button
                                            sx={{padding: '2px 6px 2px 16px'}}
                                            color="primary"
                                            variant="outlined"
                                            disabled={!item.link}
                                            startIcon={(
                                                <SvgIcon sx={{width: '24px', height: '24px'}}>
                                                    <g
                                                        strokeLinejoin="round"
                                                        strokeLinecap="round"
                                                        strokeWidth="2"
                                                        stroke="currentColor"
                                                        fill="none">
                                                        <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                                        <path d="M17 21H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7l5 5v11a2 2 0 0 1-2 2z" />
                                                        <path d="M12 17v-6" />
                                                        <path d="M9.5 14.5L12 17l2.5-2.5" />
                                                    </g>
                                                </SvgIcon>
                                            )}
                                            onClick={() => {
                                                window.open(item.link, '_blank');
                                            }}>
                                            Documento
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
                                    item: 'hint',
                                    name: 'Asunto',
                                    show: true,
                                    sort: true
                                },
                                {
                                    item: 'name',
                                    name: 'Nombre',
                                    show: true,
                                    sort: true
                                },
                                {
                                    item: 'mail',
                                    name: 'Correo',
                                    show: true,
                                    sort: true
                                },
                                {
                                    item: 'done',
                                    name: 'Estado',
                                    show: true,
                                    size: 120,
                                    cast: (item: any) => item.done === true
                                        ? <Chip label="Firmado" size="small" sx={{ backgroundColor: '#4CAF50', color: '#fff', fontWeight: 500 }} />
                                        : item.done === false
                                        ? <Chip label="Pendiente" size="small" sx={{ backgroundColor: '#F44336', color: '#fff', fontWeight: 500 }} />
                                        : <Chip label="..." size="small" variant="outlined" />
                                },
                                {
                                    item: 'date',
                                    name: 'Fecha',
                                    edge: 'right',
                                    show: true,
                                    sort: true,
                                    size: 180,
                                    cast: (item: any) => (moment(item.date).format('DD/MM/YY LT'))
                                }
                            ]}
                            take={{
                                pick: data.take,
                                list: [16,  32, 64]
                            }}
                            find={{
                                hint: 'Search',
                                text: data.find
                            }}
                            none={{
                                text: 'No Resources',
                                note: 'No available resources found.'
                            }}
                            wait={data.wait}
                            list={data.list}
                            size={data.size}
                            page={data.page}
                            load={(page, take, find, pipe, sort) => {
                                setData((last) => ({...last, page: page ?? data.page, take: take ?? data.take, sort: sort, find: find, pipe: pipe, wait: true}));
                            }} />
                    </Grid>
                </Grid>
            </Fragment>
        );
    }

    export const View = () => {
        const {
            type,
            item
        } = useParams();
    
        const {
            setAlert,
            setTitle,
            setDialog
        } = useApplication();

        const [data, setData] = useState<{
            wait: boolean,
            lock: boolean,
            done: boolean,
            fail: Boolean,
            step: string,
            pick: any
        }>({
            step: 'review',
            wait: false,
            lock: false,
            done: false,
            fail: false,
            pick: null
        });

        const [zoom, setZoom] = useState(16);

        useEffect(() => {
            Store.find(({'documentos': 1} as const)[(type as string)] ?? 1, `${item}`, (done, data) => {
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
    
        return (
            <Grid
                direction="column"
                display="flex"
                spacing={2}
                container>
                <Grid item>
                    <Typography
                        color="text.secondary"
                        variant="h4"
                        gutterBottom>
                        {({'documentos': 'Detalles'} as const)[(type as string)]}
                    </Typography>
                    <Typography
                        color="text.secondary"
                        gutterBottom>
                        {(data.done ? `Documento "${data.pick.code}"` : 'Detalles documento')}
                    </Typography>
                </Grid>
                <Grid
                    display="flex"
                    flex={1}
                    item>
                    {(data.done ? (
                        <Card
                            sx={{pt: 2, flex: 1}}
                            variant="outlined">
                            {((type == 'documentos') && (
                                <Table>
                                    <TableBody>
                                        <TableRow>
                                            <TableCell>
                                                <Typography
                                                    color="text.secondary"
                                                    gutterBottom>
                                                    Documento
                                                </Typography>
                                                <Typography
                                                    color="text.primary"
                                                    gutterBottom>
                                                    {(data.pick?.code ?? 'Ninguno')}
                                                </Typography>
                                            </TableCell>
                                            <TableCell>
                                                <Typography
                                                    color="text.secondary"
                                                    gutterBottom>
                                                    Asunto
                                                </Typography>
                                                <Typography
                                                    color="text.primary"
                                                    gutterBottom>
                                                    {(data.pick?.hint ?? 'Ninguno')}
                                                </Typography>
                                            </TableCell>
                                        </TableRow>
                                        <TableRow>
                                            <TableCell>
                                                <Typography
                                                    color="text.secondary"
                                                    gutterBottom>
                                                    Mensaje
                                                </Typography>
                                                <Typography
                                                    color="text.primary"
                                                    gutterBottom>
                                                    {(data.pick?.note ?? 'Ninguno')}
                                                </Typography>
                                            </TableCell>
                                            <TableCell>
                                                <Typography
                                                    color="text.secondary"
                                                    gutterBottom>
                                                    Nombre
                                                </Typography>
                                                <Typography
                                                    color="text.primary"
                                                    gutterBottom>
                                                    {(data.pick?.name ?? 'Ninguno')}
                                                </Typography>
                                            </TableCell>
                                        </TableRow>
                                        <TableRow>
                                            <TableCell>
                                                <Typography
                                                    color="text.secondary"
                                                    gutterBottom>
                                                    Correo
                                                </Typography>
                                                <Typography
                                                    color="text.primary"
                                                    gutterBottom>
                                                    {(data.pick?.mail ?? 'Ninguno')}
                                                </Typography>
                                            </TableCell>
                                        </TableRow>
                                    </TableBody>
                                </Table>
                            ))}
                        </Card>
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

    export const Make = () => {
        const [form, setForm] = useState<{wait: boolean, lock: boolean, fail: Hash<string>, data: Hash<any>}>({
            data: {} as Hash<any>,
            wait: false,
            lock: false,
            fail: {}
        });

        const {type} = useParams();

        const view: any = {
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
                    text: 'Documento (PDF)',
                    hint: 'Seleccione el documento PDF para firmar.',
                    bind: 'El documento es requerido.'
                },
                {
                    line: true,
                    name: 'List',
                    text: 'Firmantes',
                    hint: 'Personas que deben firmar el documento.'
                },
                {
                    type: 'turn',
                    size: 'full',
                    name: 'cell',
                    text: 'Firma desde WhatsApp'
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
                                size: 'full',
                                hint: 'Nombre completo',
                                bind: 'El nombre es requerido.'
                            },
                            {
                                type: 'text',
                                name: 'mail',
                                text: 'Correo',
                                size: 'full',
                                hint: 'Correo electrónico',
                                bind: 'El correo es requerida.'
                            },
                            {
                                type: 'text',
                                name: 'cell',
                                text: 'Celular',
                                size: 'full',
                                hint: '300 000 0000',
                                bind: 'El celular es requerida.',
                                data: 'code',
                                menu: {
                                    item: 'from',
                                    list: [{code: '+1', name: 'Estados Unidos'}, {code: '+33', name: 'Francia'}, {code: '+34', name: 'España'}, {code: '+49', name: 'Alemania'}, {code: '+51', name: 'Peru'}, {code: '+52', name: 'Mexico'}, {code: '+54', name: 'Argentina'}, {code: '+55', name: 'Brasil'}, {code: '+56', name: 'Chile'}, {code: '+57', name: 'Colombia'}, {code: '+58', name: 'Venezuela'}, {code: '+593', name: 'Ecuador'}]
                                }
                            }
                        ],
                        push: (next: number) => ({name: null, mail: null, cell: null, from: '+57'})
                    }
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
                        {({'documentos': 'Sistema de firma de documentos'} as const)[(type as string)]}
                    </Typography>
                    <Typography
                        color="text.secondary"
                        gutterBottom>
                        {({'documentos': 'Realiza aquí la firma de tus documentos.'} as const)[(type as string)]}
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
                                navigate('/firmas/documentos');
                            }
                        }}
                        save={{
                            text: 'Enviar',
                            task: async (data: Hash<any>) => {
                                setForm((last) => ({
                                    ...last,
                                    lock: true,
                                    data: {
                                        ...last.data,
                                        ...data
                                    }
                                }));
                                
                                Store.post(({'documentos': 1} as const)[(type as string)] ?? 1, data, (done: boolean, data: any) => {
                                    if (done) {
                                        setAlert((data?.text ?? 'La solicitud fue enviada con correctamente.'), 'success');

                                        setForm((last) => ({
                                            ...last,
                                            lock: false,
                                            data: {
                                                cell: null,
                                                hint: null,
                                                file: null,
                                                note: null,
                                                list: []
                                            }
                                        }));
                                    } else {
                                        setAlert((data?.text ?? 'La solicitud no pudo ser enviada con correctamente.'), 'error');

                                        setForm((last) => ({
                                            ...last,
                                            lock: false
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
}