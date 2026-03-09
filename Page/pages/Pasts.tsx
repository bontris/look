import React, {useRef, useState, useEffect, Fragment} from "react";

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
    FormHelperText,
    CircularProgress
} from "@mui/material";

import {
    useForm,
    Controller,
    SubmitHandler
} from "react-hook-form";

import {useApplication} from "../hooks/Application";

import {useSession} from "../hooks/Session";

import NoneIcon from "@mui/icons-material/Inbox";

import type {Null} from "../types/Null";

import type {Hash} from "../types/Hash";

import type {User} from "../types/User";

import {Data} from "./../components/Data";

import {Form} from "./../components/Form";

import Store from "./../services/Pasts";

type Data = {
    size: number
}

export namespace Pasts {
    export const List = ({task}: {task?: string}) => {
        const {
            setAlert,
            setTitle,
            setDialog
        } = useApplication();

        const {type} = useParams();

        const navigate = useNavigate();

        const {session} = useSession();

        const [fail, setFail] = useState({
            text: '',
            form: {
                size: ''
            }
        });
    
        const {control, handleSubmit, formState: {errors}} = useForm({
            defaultValues: {
                size: 1
            }
        });

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
            dump: {
                open: false,
                view: null
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

        const [cart, setCart] = useState<{
            fail: Hash<any>,
            wait: boolean,
            lock: boolean,
            open: boolean,
            cost: number,
            size: any
            }>({
            wait: false,
            lock: false,
            open: false,
            cost: 5000,
            fail: {},
            size: 1
        });

        const node = useRef<HTMLDivElement>(null);
                
        const dump = useReactToPrint({contentRef: node, documentTitle: 'Reporte'});

        useEffect(() => {
            Store.load(({'antecedentes': 1, 'representantes': 2} as const)[(type as string)] ?? 1, data.take, data.page, data.find, Object.keys(data.pipe).filter((name) => ((typeof data.pipe[name]['data'] == 'number') || Boolean(data.pipe[name]['data']))).map((name) => {
                return `${name}: ${(data.pipe[name]['sign'])} ${(data.pipe[name]['data'] instanceof Array ? data.pipe[name]['data'].join(' ') : data.pipe[name]['data'])}`
            }, []).join(', '), null, data.sort, (done, data) => {
                if (done) {
                    setData((last) => ({...last, wait: false, page: data.page, list: data.data, size: data.size}));
                } else {
                    //setNote({show: true, type: 'error', text: 'No se pudo cargar los registros.'});

                    setData((last) => ({...last, list: [], wait: false}));
                }
            });
        }, [data.page, data.take, data.sort, data.find, data.pipe, data.time, type]);

        const onSubmit: SubmitHandler<Data> = async ({size: size}: Data) => {
            setFail({text: '', form: {size: ''}});
    
            setCart((last) => ({
                ...last,
                wait: true
            }));
    
            setTimeout(() => {
                setCart((last) => ({
                    ...last,
                    wait: false
                }));
    
                setAlert('No se pudo realizar la compra.', 'error');
            }, 600);
        }
    
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
                            {({'antecedentes': 'Antecedentes judiciales', 'representantes':  'Antecedentes de compañias'} as const)[(type as string)]}
                        </Typography>
                        <Typography
                            color="text.secondary"
                            gutterBottom>
                            {({'antecedentes': 'Consulta aquí antecedentes judiciales de personas en Colombia, en el exterior y de empresas.', 'representantes':  'Módulo de consulta de representantes de compañias.'} as const)[(type as string)]}
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
                                    icon: ['M4 6c0 1.657 3.582 3 8 3s8-1.343 8-3s-3.582-3-8-3s-8 1.343-8 3', 'M4 6v6c0 1.657 3.582 3 8 3c1.075 0 2.1-.08 3.037-.224M20 12V6', 'M4 12v6c0 1.657 3.582 3 8 3q.249 0 .495-.006M16 19h6m-3-3v6'],
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
                                                        <path d="M4 6v6c0 1.657 3.582 3 8 3m8-3.5V6"/><path d="M4 12v6c0 1.657 3.582 3 8 3m3-3a3 3 0 1 0 6 0a3 3 0 1 0-6 0m5.2 2.2L22 22" />
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
                                                if (Boolean(session.firm?.bank)) {
                                                    setData((last) => ({
                                                        ...last,
                                                        form: {
                                                            ...last.form,
                                                            post: {
                                                                ...last.form.post,
                                                                open: true,
                                                                wait: false,
                                                                data: {
                                                                    date: null,
                                                                    type: null,
                                                                    card: null
                                                                }
                                                            }
                                                        }
                                                    }));
                                                } else {
                                                    setCart((last) => ({
                                                        ...last,
                                                        open: true,
                                                        size: "1"
                                                    }));
                                                }
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
                                    show: ((type == 'antecedentes')),
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
                                                Store.dump(1, item.hash, (done: boolean, data: any) => {
                                                    if (done) {
                                                        if (data.done) {
                                                            window.open(data.link, '_blank');
                                                        } else {
                                                            setAlert('El proceso de validación aun no ha sido completado, inténtalo más tarde.', 'error');
                                                        }
                                                    } else {
                                                        setAlert((data?.text ?? 'La solicitud no pudo ser enviada con correctamente.'), 'error');
                                                    }
                                                });
                                            }}>
                                            Descargar
                                        </Button>
                                    )
                                },
                                {
                                    icon: ['M13 5h8m-8 4h5m-5 6h8m-8 4h5M3 5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1zm0 10a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1z'],
                                    hint: 'Detalles',
                                    name: 'View',
                                    show: ((type == 'representantes')),
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
                                                navigate(`/validaciones/${type}/${item.hash}`);
                                            }}>
                                            Detalles
                                        </Button>
                                    )
                                }
                            ]}
                            data={[
                                {
                                    item: 'card',
                                    name: 'Número',
                                    show: true,
                                    sort: true
                                },
                                {
                                    item: 'made',
                                    name: 'Fecha',
                                    edge: 'right',
                                    show: true,
                                    sort: true,
                                    size: 180,
                                    cast: (item: any) => (moment(item.date).format('DD/MM/YY LT'))
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
                                text: 'No Resources',
                                note: 'No available resources found.'
                            }}
                            wait={data.wait}
                            list={data.list}
                            size={data.size}
                            page={data.page}
                            pick={(item: any, name: string) => {
                                navigate(`/Users/edit/${item.id}`);
                            }}
                            load={(page, take, find, pipe, sort) => {
                                setData((last) => ({...last, page: page ?? 1, take: take ?? 16, sort: sort, find: find, pipe: pipe, wait: true}));
                            }} />
                    </Grid>
                </Grid>
                <Dialog
                    open={(cart.open)}
                    maxWidth="md"
                    onClose={(event, reason) => {
                        if (((reason === 'backdropClick') == false)) {
                            setCart((last) => ({
                                ...last,
                                open: false
                            }));
                        }
                    }}>
                    <DialogTitle
                        sx={{px: 3, pt: 4, pb: 0}}
                        alignItems="center"
                        justifyContent="center">
                        <Typography
                            color="text.primary"
                            variant="h5"
                            gutterBottom>
                            Tokens
                        </Typography>
                        <Typography
                            color="text.secondary"
                            gutterBottom>
                            Tokens insuficientes.
                        </Typography>
                    </DialogTitle>
                    <DialogContent sx={{mt: 2}}>
                        <Grid
                            spacing={2}
                            autoComplete="off"
                            component="form"
                            onSubmit={handleSubmit(onSubmit)}
                            container
                            noValidate>
                            <Grid
                                xs={12}
                                item>
                                <Typography>
                                    No tienes tokens suficientes, ¿deseas adquirir más tokens?
                                </Typography>
                            </Grid>
                            <Grid
                                xs={12}
                                item>
                                <Stack>
                                    <TextField
                                        error={Boolean(fail.form.size)}
                                        value={cart.size}
                                        disabled={cart.wait}
                                        placeholder="Número de tokens"
                                        onInput={(event: React.ChangeEvent<HTMLInputElement>) => {
                                            setCart((last) => ({
                                                ...last,
                                                size: event.target.value,
                                                fail: /^[0-9]+$/.test(event.target?.value) ? {} : ({
                                                    size: 'Debe ser un número entero.'
                                                })
                                            }));
                                        }}
                                        fullWidth />
                                    <Typography color="text.primary">
                                        {(/^[0-9]+$/.test(cart.size) && `COP $${(cart.size * cart.cost).toLocaleString()}`)}
                                    </Typography>
                                    <FormHelperText error>
                                        {cart.fail.size}
                                    </FormHelperText>
                                </Stack>
                            </Grid>
                        </Grid>
                    </DialogContent>
                    <DialogActions sx={{padding: 3}}>
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
                                        disabled={cart.wait}
                                        onClick={(event) => {
                                            setCart((last) => ({
                                                ...last,
                                                open: false
                                            }));
                                        }}>
                                        Cancelar
                                    </Button>
                                    <Button
                                        type="submit"
                                        color="success"
                                        variant="contained"
                                        disabled={(cart.wait || (/^[0-9]+$/.test(cart.size) ? false : true))}
                                        onClick={(event) => {
                                            setCart((last) => ({
                                                ...last,
                                                wait: true
                                            }));

                                            window.open('https://checkout.wompi.co/l/h7ZGwN', '_blank');
                                    
                                            setTimeout(() => {
                                                setCart((last) => ({
                                                    ...last,
                                                    wait: false
                                                }));
                                    
                                                //setAlert('No se pudo realizar la compra.', 'error');
                                            }, 600);
                                        }}>
                                        Comprar
                                    </Button>
                                </Stack>
                            </Grid>
                        </Grid>
                    </DialogActions>
                </Dialog>
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
                            Consultar
                        </Typography>
                        <Typography
                            color="text.secondary"
                            gutterBottom>
                            Nueva consulta.
                        </Typography>
                    </DialogTitle>
                    <DialogContent sx={{pt: 1, pb: 0, width: 480}}>
                        <Form
                            data={data.form.post.data}
                            wait={data.form.post.wait}
                            lock={data.form.post.wait}
                            flat={true}
                            view={{
                                data: [
                                    {
                                        type: 'menu',
                                        size: 'full',
                                        name: 'type',
                                        hint: 'Seleccione una opción',
                                        text: 'Tipo de documento',
                                        bind: 'La opción es requerida.',
                                        hide: ((type == 'representantes')),
                                        list: [
                                            {item: 'NIT', text: 'Número de Identificación Tributaria'},
                                            {item: 'CC', text: 'Cédula de ciudadanía'},
                                            {item: 'CE', text: 'Cédula de extranjería'},
                                            {item: 'PPT', text: 'Permiso por Protección Temporal'}
                                        ]
                                    },
                                    {
                                        type: 'text',
                                        size: 'full',
                                        name: 'card',
                                        text: 'Número de documento',
                                        bind: 'La opción es requerida.'
                                    },
                                    {
                                        type: 'date',
                                        size: 'full',
                                        name: 'date',
                                        text: 'Fecha de expedición',
                                        hide: ((type == 'representantes'))
                                    }
                                ]
                            } as any}
                            quit={{
                                task: (step, exit) => {
                                    setData((last) => ({...last, form: {...last.form, post: {...last.form.post, open: false}}}));
                                }
                            }}
                            save={{
                                text: 'Buscar',
                                task: (form: any, step?: number, save?: boolean) => {
                                    Store.post(({'antecedentes': 1, 'representantes': 2} as const)[(type as string)] ?? 1, form, (done: boolean, data: any) => {
                                        if (done) {
                                            if (data.view) {
                                                setData((last) => ({
                                                    ...last,
                                                    time: (new Date).getTime(),
                                                    form: {
                                                        ...last.form,
                                                        post: {
                                                            ...last.form.post,
                                                            open: false
                                                        }
                                                    },
                                                    dump: {
                                                        ...last.dump,
                                                        view: data.view,
                                                        open: true
                                                    }
                                                }));
                                            } else {
                                                setData((last) => ({...last, time: (new Date).getTime(), form: {...last.form, post: {...last.form.post, wait: false}}}));

                                                setAlert((data?.text ?? 'La solicitud fue enviada con correctamente.'), 'success');
                                            }
                                        } else {
                                            setAlert((data?.text ?? 'La solicitud no pudo ser enviada con correctamente.'), 'error');
                                        }
                                    });

                                    setData((last) => ({...last, form: {...last.form, post: {...last.form.post, wait: true}}}));
                                }
                            }} />
                    </DialogContent>
                </Dialog>
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
                            Reporte
                        </Typography>
                        <Typography
                            color="text.secondary"
                            gutterBottom>
                            Reporte de consulta de antecedentes.
                        </Typography>
                    </DialogTitle>
                    <DialogContent ref={node}>
                        <Box
                            sx={{'@media print': {
                                '@page': {
                                    size: '16in 12in',
                                    margin: '2mm'
                                }
                                }}}
                            ref={node}
                            dangerouslySetInnerHTML={{__html: (data.dump.view ?? '')}} />
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
                                        onClick={(event) => {
                                            setData((last) => ({...last, dump: {...last.dump, open: false}}));
                                        }}>
                                        Cancelar
                                    </Button>
                                    <Button
                                        type="reset"
                                        color="primary"
                                        variant="contained"
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
            Store.find(2, `${item}`, (done, data) => {
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

            setTitle('Reporte');
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
                        Validación de Representantes
                    </Typography>
                    {(data.done ? (
                        <Typography
                            color="text.secondary"
                            gutterBottom>
                            Resultados para "{data.pick.card}"
                        </Typography>
                    ) : (
                        <Typography
                            color="text.secondary"
                            gutterBottom>
                            Resultados de validación
                        </Typography>
                    ))}
                </Grid>
                <Grid
                    display="flex"
                    flex={1}
                    item>
                    {(data.done ? (
                        <Card
                            sx={{pt: 2, flex: 1}}
                            variant="outlined">
                            <Table>
                                <TableBody>
                                    <TableRow>
                                        <TableCell>
                                            <Typography color="text.secondary">
                                                Identificación
                                            </Typography>
                                            <Typography color="text.primary">
                                                {(data.pick?.data?.identificacion ?? 'Ninguna')}
                                            </Typography>
                                        </TableCell>
                                        <TableCell>
                                            <Typography color="text.secondary">
                                                Estado
                                            </Typography>
                                            <Typography color="text.primary">
                                                {(data.pick?.data?.estadoRM ?? 'Ninguno')}
                                            </Typography>
                                        </TableCell>
                                    </TableRow>
                                    <TableRow>
                                        <TableCell>
                                            <Typography color="text.secondary">
                                                Razón Social
                                            </Typography>
                                            <Typography color="text.primary">
                                                {(data.pick?.data?.razon_social ?? 'Ninguna')}
                                            </Typography>
                                        </TableCell>
                                        <TableCell>
                                            <Typography color="text.secondary">
                                                Sigla
                                            </Typography>
                                            <Typography color="text.primary">
                                                {(data.pick?.data?.sigla ?? 'Ninguna')}
                                            </Typography>
                                        </TableCell>
                                    </TableRow>
                                    <TableRow>
                                        <TableCell colSpan={2}>
                                            <Typography color="text.secondary">
                                                Categoría Matrícula
                                            </Typography>
                                            <Typography color="text.primary">
                                                {(data.pick?.data?.categoria_matricula ?? 'Ninguna')}
                                            </Typography>
                                        </TableCell>
                                    </TableRow>
                                    <TableRow>
                                        <TableCell colSpan={2}>
                                            <Typography color="text.secondary">
                                                Detalle
                                            </Typography>
                                            <Typography color="text.primary" dangerouslySetInnerHTML={{ __html: (data.pick?.data?.detalleRM ?? 'Ninguno')}} />
                                        </TableCell>
                                    </TableRow>
                                </TableBody>
                            </Table>
                        </Card>
                    ) : ((data.fail ? (
                        <Card
                            sx={{pt: 2, flex: 1, display: 'flex'}}
                            variant="outlined">
                            <Stack
                                justifyContent="center"
                                alignItems="center"
                                display="flex"
                                flex={1}>
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
                            </Stack>
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