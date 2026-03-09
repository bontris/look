import {
    ReactNode,
    useRef,
    useState,
    useEffect
} from "react";

import type {
    MouseEvent,
    ReactElement
} from "react";

import {
    useNavigate,
    useLocation
} from "react-router-dom";

import {
    useForm,
    Controller,
    SubmitHandler
} from "react-hook-form";

import {
    Box,
    Fade,
    Grid,
    List,
    alpha,
    Stack,
    Paper,
    Alert,
    Badge,
    Popper,
    AppBar,
    Dialog,
    Drawer,
    Avatar,
    Button,
    Divider,
    Toolbar,
    Tooltip,
    SvgIcon,
    Backdrop,
    Collapse,
    Snackbar,
    MenuList,
    MenuItem,
    ListItem,
    InputBase,
    TextField,
    Typography,
    IconButton,
    DialogTitle,
    FormControl,
    ListItemIcon,
    ListItemText,
    DialogContent,
    DialogActions,
    ListSubheader,
    FormHelperText,
    ListItemAvatar,
    ListItemButton,
    InputAdornment,
    CircularProgress,
    ClickAwayListener
} from "@mui/material";

import moment from "moment";

import {useTheme} from "@mui/material/styles";

import {useApplication} from "./../hooks/Application";

import {useSettings} from "./../hooks/Settings";

import {useSession} from "./../hooks/Session";

import {HEAD, SIDE, MODE, TEXT, IDLE, NAME, TINT} from "./../environment";

import type {Null} from "../types/Null";

import type {Hash} from "../types/Hash";
import { point } from "leaflet";

type Data = {
    size: number
}

const Menu = ({children, icon, find, open, hold, exit}: {
    children: ReactElement | ((text: Null<string>) => ReactElement),
    icon: ReactNode,
    find?: string,
    open?: boolean,
    hold?: boolean,
    exit?: () => void
}) => {
    const [menu, setMenu] = useState({
        node: useRef<HTMLDivElement>(null),
        open: false
    });

    const [text, setText] = useState<Null<string>>(null);

    const hide = (event?: MouseEvent<HTMLLIElement> | (MouseEvent | TouchEvent), path?: string) => {
        if (((event?.target instanceof HTMLBodyElement) == false)) {
            setMenu((last) => ({
                ...last,
                open: false
            }));

            if (exit) {
                exit();
            }
        }
    }

    useEffect(() => {
        setMenu((last) => ({
            ...last,
            open: open ?? false
        }));
    }, [open]);

    return (
        <Box sx={{flexGrow: 0}}>
            <Box
                ref={menu.node}
                onClick={() => {
                    setMenu((last) => ({
                        ...last,
                        open: true
                    }));
                }}>
                {icon}
            </Box>
            <Popper
                sx={{zIndex: (theme) => (theme.zIndex.drawer + 1), borderRadius: '6px'}}
                open={menu.open}
                modifiers={[
                    {
                        name: 'offset',
                        options: {
                            offset: [0, 8]
                        }
                    }
                ]}
                anchorEl={menu.node.current}
                placement="bottom"
                onClick={(hold ? undefined : hide)}
                transition>
                {({TransitionProps, placement}) => (
                    <Fade
                        {...TransitionProps}
                        style={{
                            transformOrigin: (placement == 'bottom-end') ? 'right top' : 'left top'
                        }}>
                        <Paper className="shadow">
                            <ClickAwayListener onClickAway={event => hide(event as MouseEvent | TouchEvent)}>
                                <Stack>
                                    {(Boolean(find) && (
                                        <Box sx={{px: 1, pt: 1}}>
                                            <TextField
                                                placeholder={find}
                                                autoComplete="off"
                                                InputProps={{
                                                    startAdornment: (
                                                        <InputAdornment position="start">
                                                            <SvgIcon sx={{width: '22px', height: '22px'}}>
                                                                <path
                                                                    strokeLinejoin="round"
                                                                    strokeLinecap="round"
                                                                    strokeWidth="2"
                                                                    stroke="currentColor"
                                                                    fill="none"
                                                                    d="M3 10a7 7 0 1 0 14 0a7 7 0 1 0-14 0m18 11l-6-6" />
                                                            </SvgIcon>
                                                        </InputAdornment>
                                                    ),
                                                    endAdornment: (
                                                        <IconButton
                                                            sx={{margin: '0px 0px 0px 8px', padding: '4px', visibility: (Boolean(text?.trim()) ? 'visible' : 'hidden')}}
                                                            onClick={(event) => {
                                                                setText(null);
                                                            }}>
                                                            <SvgIcon sx={{width: '18px', height: '18px'}}>
                                                                <path
                                                                    strokeLinejoin="round"
                                                                    strokeLinecap="round"
                                                                    strokeWidth="2"
                                                                    d="M12 2c5.523 0 10 4.477 10 10s-4.477 10-10 10S2 17.523 2 12S6.477 2 12 2m3.6 5.2a1 1 0 0 0-1.4.2L12 10.333L9.8 7.4a1 1 0 1 0-1.6 1.2l2.55 3.4l-2.55 3.4a1 1 0 1 0 1.6 1.2l2.2-2.933l2.2 2.933a1 1 0 0 0 1.6-1.2L13.25 12l2.55-3.4a1 1 0 0 0-.2-1.4" />
                                                            </SvgIcon>
                                                        </IconButton>
                                                    )
                                                }}
                                                onChange={((event) => {
                                                    setText(event.target.value.trim());
                                                })}
                                                autoFocus
                                                fullWidth />
                                        </Box>
                                    ))}
                                    {(children instanceof Function ? children(text) : children)}
                                </Stack>
                            </ClickAwayListener>
                        </Paper>
                    </Fade>
                )}
            </Popper>
        </Box>
    );
}

export const Head = () => {
    const {mode, text, side, live, busy, pages, notifications, setMode, setText, setSide, setAlert} = useApplication();

    const {session, exit, ring} = useSession();

    const navigate = useNavigate();

    const theme = useTheme();

    const [fail, setFail] = useState({
        text: '',
        form: {
            size: ''
        }
    });

    const {control, setValue, handleSubmit, formState: {errors}} = useForm({
        defaultValues: {
            size: 1
        }
    });

    useEffect(() => {
        setFail((last) => ({...last, form: {...last.form, size: errors.size?.message ?? ''}}));
    }, [errors.size]);

    const [cart, setCart] = useState<{
        ring: boolean,
        wait: boolean,
        lock: boolean,
        open: boolean,
        cost: number
    }>({
        ring: false,
        wait: false,
        lock: false,
        open: false,
        cost: 5000
    });

    const [bell, setBell] = useState<{
        list: Array<any>,
        time: number
    }>({
        list: [],
        time: 0
    });

    const onSubmit: SubmitHandler<Data> = async ({size: size}: Data) => {
        setFail({text: '', form: {size: ''}});

        setCart((last) => ({
            ...last,
            wait: true,
            open: true
        }));

        setTimeout(() => {
            setCart((last) => ({
                ...last,
                wait: false,
                open: false
            }));

            setValue('size', 1);

            window.open('https://checkout.wompi.co/l/eQgj1C', '_blank');
        }, 600);
    }

    useEffect(() => {
        (async (type: number) => {
            const data = await ring(type);

            if (data.done) {
                setBell((last) => ({
                    ...last,
                    list: data.list
                }));
            }
        })(0)
    }, [bell.time])

    useEffect(() => {
        setInterval(() => {
            setBell((last) => ({
                ...last,
                time: Date.now()
            }))
        }, 30000);
    }, [])

      

    return (
        <AppBar
            sx={{
                height: HEAD,
                zIndex: (theme) => theme.zIndex.drawer + 1
            }}
            elevation={0}>
            <Box sx={{height: '100%', display: 'flex', alignItems: 'center'}}>
                <Stack
                    sx={{px: 2, height: '100%', background: TINT}}
                    gap={1}
                    width={SIDE - 32}
                    direction="row"
                    alignItems="center">
                    <IconButton onClick={(event) => {
                        setSide((side ? false : true));
                    }}>
                        <SvgIcon sx={{width: '24px', height: '24px'}}>
                            <path
                                strokeLinejoin="round"
                                strokeLinecap="round"
                                strokeWidth="2"
                                stroke="currentColor"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </SvgIcon>
                    </IconButton>
                    <Box
                        component="img"
                        src="/images/dark.png"
                        sx={{margin: '8px 16px', width: 128,  height: 32}} />
                </Stack>
                {(Boolean(session.firm?.name) && (
                    <Stack sx={{px: 2}}>
                        <Typography color="text.primary">
                            {(session.firm?.name ?? NAME)}
                        </Typography>
                        <Typography color="text.secondary">
                            Empresa
                        </Typography>
                    </Stack>
                ))}
                <Box sx={{mr: '8px', flexGrow: 1}}>
                </Box>
                <Stack
                    direction="row"
                    gap={2}
                    sx={{px: 2}}>
                    {([1, 2].includes(session.firm?.plan) && (
                        <Button
                            sx={{visibility: 'hidden'}}
                            href="/servicios"
                            startIcon={(
                                <SvgIcon sx={{width: 24, height: 24}}>
                                    <g
                                        strokeLinejoin="round"
                                        strokeLinecap="round"
                                        strokeWidth="2"
                                        stroke="currentColor"
                                        fill="none">
                                        <path d="M10.5 21H6a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v3m-4-7v4M8 3v4m-4 4h10" />
                                        <path d="M14 18a4 4 0 1 0 8 0a4 4 0 1 0-8 0" />
                                        <path d="M18 16.5V18l.5.5" />
                                    </g>
                                </SvgIcon>
                            )}
                            endIcon={(
                                <SvgIcon sx={{width: 24, height: 24}}>
                                    <g
                                        strokeLinejoin="round"
                                        strokeLinecap="round"
                                        strokeWidth="2"
                                        stroke="currentColor"
                                        fill="none">
                                        <path d="M3 9a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2zm5-2V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m-4 5v.01" />
                                        <path d="M3 13a20 20 0 0 0 18 0" />
                                    </g>
                                </SvgIcon>
                            )}
                            color="primary"
                            variant="contained">
                            {moment.utc((session.firm?.load * 1000)).format('HH:mm')} / {moment.utc(((session.firm?.time * 3600) * 1000)).format('HH:mm')}
                        </Button>
                    ))}
                    {((session.type == 4) && (
                        <Menu icon={
                            <Tooltip title="Carrito">
                                <IconButton sx={{margin: 0, background: '#FFFFFF'}}>
                                    <SvgIcon>
                                        <g
                                            strokeLinejoin="round" 
                                            strokeLinecap="round"
                                            strokeWidth="2"
                                            stroke="currentColor"
                                            fill="none">
                                            <path d="M4 19a2 2 0 1 0 4 0a2 2 0 0 0-4 0" />
                                            <path d="M12.5 17H6V3H4" />
                                            <path d="m6 5l14 1l-.86 6.017M16.5 13H6m10 6h6m-3-3v6" />
                                        </g>
                                    </SvgIcon>
                                </IconButton>
                            </Tooltip>
                        }
                        open={cart.open}
                        hold={true}>
                            <Grid
                                sx={{pt: 2, pb: 4, px: 4, width: 620}}
                                spacing={2}
                                autoComplete="off"
                                component="form"
                                onSubmit={handleSubmit(onSubmit)}
                                container
                                noValidate>
                                <Grid
                                    sx={{mb: 1}}
                                    xs={12}
                                    item>
                                    <Typography
                                        variant="h6"
                                        gutterBottom>
                                        Compra de tokens
                                    </Typography>
                                    <Typography
                                        variant="body1"
                                        gutterBottom>
                                        Compra aquí tokens para firma de documentos y validación de antecedentes.
                                    </Typography>
                                    <Typography
                                        variant="caption"
                                        gutterBottom>
                                        Se requiere un 1 token por cada servicio de firma y de validación de antecedentes.
                                    </Typography>
                                </Grid>
                                <Grid
                                    xs={12}
                                    item>
                                    <Controller
                                        name="size"
                                        control={control}
                                        rules={{required: 'El campo es requerido', pattern: {
                                            value: /[0-9]+/,
                                            message: 'El valor no es un número válido.',
                                        }}}
                                        render={({field}) => (
                                            <FormControl
                                                {...field}
                                                error={Boolean(fail.form.size)}
                                                fullWidth>
                                                <Stack
                                                    spacing={2}
                                                    direction="row">
                                                    <TextField
                                                        id="size"
                                                        value={field.value}
                                                        disabled={cart.wait}
                                                        placeholder="Número de tokens"
                                                        fullWidth />
                                                        <Button
                                                            sx={{padding: '2px 6px 2px 16px'}}
                                                            type="submit"
                                                            color="primary"
                                                            variant="contained"
                                                            disabled={cart.lock}
                                                            startIcon={(
                                                                <SvgIcon sx={{width: '24px', height: '24px'}}>
                                                                    <g
                                                                        strokeLinejoin="round"
                                                                        strokeLinecap="round"
                                                                        strokeWidth="2"
                                                                        stroke="currentColor"
                                                                        fill="none">
                                                                        <path d="M4 19a2 2 0 1 0 4 0a2 2 0 1 0-4 0m11 0a2 2 0 1 0 4 0a2 2 0 1 0-4 0"/>
                                                                        <path d="M17 17H6V3H4" />
                                                                        <path d="m6 5l14 1l-1 7H6" />
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
                                                            Comprar
                                                        </Button>
                                                    </Stack>
                                                    {(fail.form.size && (
                                                        <FormHelperText>{fail.form.size}</FormHelperText>
                                                    ))}
                                                    <Typography color="text.primary">
                                                        ${(field.value * cart.cost).toLocaleString()}
                                                    </Typography>
                                            </FormControl>
                                        )} />
                                </Grid>
                            </Grid>
                        </Menu>
                    ))}
                    <Menu icon={(
                        <IconButton sx={{margin: 0, background: '#FFFFFF'}}>
                            <Badge
                                anchorOrigin={{vertical: 'top', horizontal: 'right'}}
                                invisible={(bell.list.length == 0)}
                                overlap="circular"
                                variant="dot"
                                color="error"
                                sx={{
                                    '& .MuiBadge-dot': {
                                        top: 6,
                                        right: 6,
                                        boxShadow: (theme) => (`${theme.palette.background.paper} 0px 0px 0px 2px`)
                                    }
                                }}>
                                <SvgIcon>
                                    <path
                                        strokeLinejoin="round" 
                                        strokeLinecap="round"
                                        strokeWidth="2"
                                        stroke="currentColor"
                                        fill="none"
                                        d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3H4a4 4 0 0 0 2-3v-3a7 7 0 0 1 4-6M9 17v1a3 3 0 0 0 6 0v-1"/>
                                </SvgIcon>
                            </Badge>
                        </IconButton>
                    )}>
                        <List sx={{width: '280px', padding: 1}}>
                            {bell.list.map((item) => (
                                <ListItemButton
                                    sx={{
                                        margin: 0,
                                        gap: '12px',
                                    }}
                                    key={item.code}
                                    onClick={() => {
                                        if (item.link) {
                                            window.open(item.link, '_blank');
                                        }
                                        //setMode((item.code as 'dark' | 'light' | 'system'));
                                    }}>
                                    <ListItemIcon>
                                        <Avatar
                                            sx={(theme) => ({
                                                width: 40,
                                                height: 40,
                                                background: alpha({1: '#2196F3', 2: '#2E7D32', 3: '#FFD600', 4: '#D32F2F'}[item.type as number] ?? '#607D8B', 0.16)
                                            })}
                                            
                                            variant="circular">
                                            <SvgIcon sx={{width: 24, height: 24}}>
                                                <g
                                                    strokeLinejoin="round" 
                                                    strokeLinecap="round"
                                                    strokeWidth="2"
                                                    stroke={({1: '#2196F3', 2: '#2E7D32', 3: '#FFD600', 4: '#D32F2F'}[item.type as number] ?? '#607D8B')}
                                                    fill="none">
                                                    <path d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3H4a4 4 0 0 0 2-3v-3a7 7 0 0 1 4-6M9 17v1a3 3 0 0 0 6 0v-1" />
                                                </g>
                                            </SvgIcon>
                                        </Avatar>
                                    </ListItemIcon>
                                    <ListItemText
                                        sx={{ml: 1}}
                                        primary={(
                                            <Typography color="text.primary">
                                                {item.name}
                                            </Typography>
                                        )}
                                        secondary={(
                                            <Stack>
                                                <Typography color="text.secondary">
                                                    {item.note}
                                                </Typography>
                                                <Typography
                                                    variant="caption"
                                                    color="text.secondary">
                                                    {moment(item.made).format('MM/DD/YY LT')}
                                                </Typography>
                                            </Stack>
                                        )} />
                                </ListItemButton>
                            ))}
                        </List>
                    </Menu>
                    <Menu icon={
                        <Stack
                            direction="row"
                            gap={2}
                            sx={{cursor: 'pointer'}}>
                            <Badge
                                overlap="circular"
                                anchorOrigin={{vertical: 'bottom', horizontal: 'right'}}
                                badgeContent={
                                    <Box
                                        component="span"
                                        sx={
                                            {
                                                width: 8,
                                                height: 8,
                                                borderRadius: '50%',
                                                cursor: 'pointer',
                                                backgroundColor: IDLE[(session.idle ?? 1)],
                                                boxShadow: (theme) => (`0 0 0 2px ${theme.palette.background.paper}`)
                                            }
                                        } />
                                }>
                                <Avatar
                                    alt={`${([session.name, session.last].filter(Boolean).join(' ') || (session.nick ?? session.mail))}`}
                                    sx={{width: 38, height: 38, cursor: 'pointer'}} />
                            </Badge>
                            <Stack>
                                <Typography color="text.primary">
                                    {([session.name, session.last].filter(Boolean).join(' ') || (session.nick ?? session.mail))}
                                </Typography>
                                <Typography
                                    variant="caption"
                                    color="text.secondary">
                                    {{1: 'Administrador', 2: 'Abogado Líder', 3: 'Abogado Marcas', 4: 'Gerente', 5: 'Agente'}[session.type]}
                                </Typography>
                            </Stack>
                        </Stack>
                    }>
                        <MenuList sx={{width: '260px'}}>
                            <MenuItem>
                                <Badge
                                    overlap="circular"
                                    anchorOrigin={{vertical: 'bottom', horizontal: 'right'}}
                                    badgeContent={
                                        <Box
                                            component="span"
                                            sx={
                                                {
                                                    width: 8,
                                                    height: 8,
                                                    borderRadius: '50%',
                                                    cursor: 'pointer',
                                                    backgroundColor: IDLE[session.idle ?? 0],
                                                    boxShadow: (theme) => (`0 0 0 2px ${theme.palette.background.paper}`)
                                                }
                                            } />
                                    }>
                                    <Avatar
                                        alt={`${([session.name, session.last].filter(Boolean).join(' ') || (session.nick ?? session.mail))}`}
                                        sx={{width: 38, height: 38}} />
                                </Badge>
                                <Box>
                                    <Typography className='font-medium' color='text.primary'>
                                        {([session.name, session.last].filter(Boolean).join(' ') || (session.nick ?? session.mail))}
                                    </Typography>
                                    <Typography variant="caption">
                                        {(session.mail ?? session.nick)}
                                    </Typography>
                                </Box>
                            </MenuItem>
                            {/*<Divider />
                            <MenuItem>
                                <SvgIcon>
                                    <path
                                        strokeLinejoin="round" 
                                        strokeLinecap="round"
                                        strokeWidth="2"
                                        stroke="currentColor"
                                        fill="none"
                                        d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0-8 0M6 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2"/>
                                </SvgIcon>
                                Mi perfil
                            </MenuItem>
                            <MenuItem>
                                <SvgIcon>
                                    <g
                                        strokeLinejoin="round" 
                                        strokeLinecap="round"
                                        strokeWidth="2"
                                        stroke="currentColor"
                                        fill="none">
                                        <path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 0 0-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 0 0-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 0 0-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 0 0-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 0 0 1.066-2.573c-.94-1.543.826-3.31 2.37-2.37c1 .608 2.296.07 2.572-1.065"/>
                                        <path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0-6 0"/>
                                    </g>
                                </SvgIcon>
                                Configuración
                            </MenuItem>*/}
                            <Divider />
                            <MenuItem onClick={async () => {
                                const pass = localStorage.getItem('pass');

                                if (pass) {
                                    let response = await exit(pass);

                                    if (response.done) {
                                        localStorage.removeItem('pass');

                                        navigate(response.next, {replace: true});
                                    } else {
                                        setAlert('Could not log out successfully', 'error');
                                    }
                                }
                            }}>
                                <SvgIcon>
                                    <g
                                        strokeLinejoin="round" 
                                        strokeLinecap="round"
                                        strokeWidth="2"
                                        stroke="currentColor"
                                        fill="none">
                                        <path d="M14 8V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2-2v-2"/>
                                        <path d="M9 12h12l-3-3m0 6l3-3"/>
                                    </g>
                                </SvgIcon>
                                Salir
                            </MenuItem>
                        </MenuList>
                    </Menu>
                </Stack>
            </Box>
        </AppBar>
    )
}