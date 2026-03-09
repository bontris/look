import React, {
    ReactElement,
    MouseEvent,
    ReactNode,
    Fragment,
    useRef,
    useMemo,
    useState,
    useEffect,
    useCallback,
    useLayoutEffect
} from "react";

import {
    Box,
    Link,
    List,
    Fade,
    Grid,
    Card,
    alpha,
    Badge,
    Paper,
    Stack,
    Table,
    Select,
    Popper,
    Dialog,
    Button,
    Avatar,
    Toolbar,
    Tooltip,
    Divider,
    SvgIcon,
    Checkbox,
    MenuList,
    TableRow,
    MenuItem,
    TextField,
    TableBody,
    TableCell,
    TableHead,
    CardHeader,
    InputLabel,
    Typography,
    Pagination,
    IconButton,
    CardContent,
    FormControl,
    DialogTitle,
    ToggleButton,
    ListItemText,
    Autocomplete,
    OutlinedInput,
    DialogContent,
    DialogActions,
    InputAdornment,
    TableContainer,
    ListItemButton,
    CircularProgress,
    DialogContentText,
    ClickAwayListener,
    SelectChangeEvent,
    ToggleButtonGroup
} from "@mui/material";

import InputMask from "react-input-mask";

import Chart from "react-apexcharts";

import dayjs from "dayjs";

import {DatePicker} from "@mui/x-date-pickers/DatePicker";

import {AdapterDayjs} from "@mui/x-date-pickers/AdapterDayjs";

import {LocalizationProvider} from "@mui/x-date-pickers";

import NoneIcon from "@mui/icons-material/Inbox";

import type {Null} from "./../types/Null";

import type {Hash} from "./../types/Hash";

type Item = {
    [name: string]: any;
};

type Knob = {
    icon?: string | Array<string>,
    type: 'push' | 'menu',
    name: string,
    text?: string,
    hint?: string,
    path?: string,
    show?: boolean,
    view?: (lock: boolean) => ReactNode,
    task?: () => void
}

type Pick = {
    item: string | number,
    text: string,
    tint?: string
}

type Cell = {
    type?: 'turn' | 'text' | 'date' | 'list' | 'menu',
    edge?: 'center' | 'right' | 'left',
    item: string;
    name: string;
    rule?: RegExp,
    hint?: string,
    case?: boolean,
    wrap?: boolean,
    show?: boolean,
    sort?: boolean,
    join?: boolean,
    size?: string | number,
    pick?: Array<Pick>,
    cast?: (item: any) => ReactNode,
    view?: (item: any) => ReactNode,
    save?: (item: any, pick: any) => void,
    text?: (item: any, next?: any) => Null<string>,
    back?: (item: any, next?: any) => Null<string>
}

type Sort = {
    type: 'text' | 'date' | 'list';
    size: 'tiny' | 'half' | 'full';
    list?: Array<any>;
    hint?: string;
    name: string;
    text: string;
    data?: any;
}

const Menu = ({children, icon, find, hold}: {
    children: ReactElement | ((text: Null<string>) => ReactElement),
    icon: ReactNode,
    find?: string,
    hold?: boolean
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
        }
    }

    const open = () => {
        setMenu((last) => ({
            ...last,
            open: true
        }));
    }

    return (
        <Box sx={{flexGrow: 0}}>
            <Box ref={menu.node} onClick={open}>
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

export const Data = (properties: {
    wait?: boolean | {dash: string, item: any},
    data: Array<Cell>,
    list: Array<any>,
    size?: number,
    page: number,
    seek: string,
    name: string,
    mode?: number,
    flex?: boolean,
    take?: {
        pick: number,
        list?: Array<number>
    },
    find?: {
        hint: string,
        text: Null<string>
    },
    none?: {
        text: string,
        note: string
    },
    flat?: boolean,
    bulk?: boolean,
    more?: boolean,
    menu?: Array<Knob>,
    tint?: (item: any, next?: any) => Null<string>,
    pick?: (item: any, name: string) => void,
    open?: (item: any) => Promise<boolean>,
    show?: (item: any, show: boolean) => void,
    load?: (page?: Null<number>, take?: Null<number>, find?: Null<string>, pipe?: any, sort?: any) => void,
    view?: (item: any, size?: number) => ReactNode,
    dash?: Array<{
        icon: string | Array<string> | ((item: any) => string | Array<string>),
        tint?: string | ((item: any) => Null<string>),
        size?: number | ((item: any) => number),
        view?: (item: any, lock: boolean) => ReactNode,
        lock?: (item: any) => boolean,
        task?: (item: any) => void,
        show?: boolean,
        bulk?: boolean,
        fill?: boolean,
        hint?: string,
        name: string
    }>,
    pane?: Array<{
        icon?: string | Array<string>,
        size?: 'tiny' | 'half',
        tint?: string,
        text: string,
        data: number
    }>,
    type?: Null<{
        hint: string,
        wait?: boolean,
        none?: string,
        find?: Null<string>,
        list?: Null<Array<{
            item: number | string | null,
            icon?: Array<string>,
            tint?: string,
            size?: number,
            text: string
        }>>,
        pick?: Null<number | string>,
        load?: (text: Null<string>) => void,
        task: (item: number | string | null) => void
    }>,
    pipe?: {
        data: Hash<{
            sign: '~' | '=' | '<' | '>' | '!',
            data: any
        }>,
        list: Hash<{
            type: 'text' | 'date' | 'menu' | 'list',
            mask?: Array<string> | string,
            date?: string,
            sign: {
                pick: '~' | '=' | '<' | '>' | '!',
                list: Array<{
                    item: '~' | '=' | '<' | '>' | '!',
                    span?: boolean,
                    text: string
                }>
            },
            menu?: {
                pick: string | number,
                list: Array<{
                    item: Null<string | number>,
                    text: string
                }>
            },
            name: string,
            hint?: string,
            data?: any
        }>
    },
    sort?: {
        once?: boolean,
        list: Hash<string>,
        data: Hash<boolean>
    },
    plot?: Array<Hash<{
        options: Hash<any>,
        series: Array<any>,
        type: string,
        size: number,
        name: string,
        hint: string,
        show: boolean
    }>>,
    edit?: {
        text?: string,
        bulk?: boolean,
        show?: (item: any) => boolean,
        lock?: (item: any) => boolean,
        path?: (item: any) => string,
        task?: (item: any) => void
    };
    drop?: {
        text?: string,
        bulk?: boolean,
        show?: (item: any) => boolean,
        lock?: (item: any) => boolean,
        path?: (item: any) => string,
        task?: (item: any) => void;
    };
}) => {
    const [time, setTime] = useState<NodeJS.Timeout | number | undefined>((void null));

    const [card, setCard] = useState((properties.mode == 2));

    const [drop, setDrop] = useState(false);

    const [draw, setDraw] = useState(false);

    const [pick, setPick] = useState([]);

    const [mark, setMark] = useState<Null<{
        head: any,
        item: any
    }>>(null);

    const skip = useRef(true);

    const view = useRef<HTMLDivElement>(null);

    const [form, setForm] = useState<{data: any, busy: boolean, down: boolean, span: boolean, page: Null<number>, type: Hash<any>, take: Null<number>, item: Null<string>, text: Null<string>, pick: Null<Hash<any>>, sort: Hash<boolean>, pipe: Hash<any>, menu: Null<string | number>, sign: Null<'~' | '=' | '<' | '>' | '!'>}>({
        pick: null,
        data: null,
        menu: null,
        sign: null,
        item: null,
        down: false,
        span: false,
        busy: false,
        page: properties.page,
        take: properties.take?.pick,
        text: properties.find?.text,
        type: {
            data: properties.type?.pick,
            find: null
        },
        sort: Object.keys((properties.sort?.list ?? {})).reduce((hash: any, name: string) => {
            if (((properties.sort?.once == false) || (Object.keys(hash).length == 0))) {
                if (properties.sort?.data?.[name]) {
                    hash[name] = properties.sort?.data[name];
                }
            }

            return hash;
        }, {}),
        pipe: Object.keys((properties.pipe?.list ?? {}))?.reduce((hash: any, name: string) => {
            if (properties.pipe?.data) {
                if (properties.pipe?.data[name]) {
                    if (properties.pipe?.data[name]['data']) {
                        hash[name] = properties.pipe?.data[name];
                    }
                }
            }

            return hash;
        }, {})
    });

    const hook = new IntersectionObserver((list) => {
        if ((list[0].intersectionRatio > 0)) {
            if (((properties.page * (properties.take?.pick ?? 16)) < (properties.size ?? properties.list?.length))) {
                if ((form.busy == false)) {
                    setForm((last) => ({
                        ...last,
                        busy: true,
                        page: properties.page + 1
                    }));
                }
            }
        }
    });

    const list = useMemo(() => {
        let join = properties.data?.filter((item) => (item.join));

        if (join.length) {
            return properties.list?.reduce((list: Array<Hash<any>>, item: any) => {
                let slot = list.find((next) => ((Object.keys(next.type).some((name) => ((next.type[name] == item[name]) == false)) == false)));

                if (slot) {
                    slot.data.push(item);
                } else {
                    list.push({type: join.reduce((hash: any, head: any) => {
                        hash[head.item] = item[head.item];

                        return hash;
                    }, {}), data: [item]});
                }

                return list;
            }, []);
        }

        return (Boolean(properties.list?.length) ? [{data: properties.list}] : []);
    }, [properties.list]);

    const dash = useMemo(() => {
        return properties.dash?.filter((item) => (item.show ?? true)) ?? [];
    }, [properties.dash]);

    const menu = useMemo(() => {
        return properties.menu?.filter((item) => (item.show ?? true)) ?? [];
    }, [properties.menu]);

    useLayoutEffect(() => {
        if (view.current) {
            if (properties.more) {
                hook.observe(view.current);
            }
        }
    }, [hook]);

    /*useEffect(() => {
        window.addEventListener('scroll', () => {
            if (((document.body.scrollHeight - 300) < (window.scrollY + window.innerHeight))) {
                console.log('load more')
            }
        });
    }, []);*/

    useEffect(() => {
        if (skip.current) {
            skip.current = false;
        } else {
            if (time) {
                clearTimeout(time);
            }
    
            setTime(setTimeout(() => {
                if (properties.load) {
                    properties.load(form.page, form.take, form.text, form.pipe, form.sort);
                }
            }, form.busy ? 0 : 600));
        }
    }, [form.page, form.take, form.pipe, form.text, form.sort]);

    useEffect(() => {
        setForm((last) => ({
            ...last,
            busy: false
        }));
    }, [properties.list]);

    const make = (item: any, live: boolean, auto: boolean, save: (save: boolean, data: any) => void) => {
        let data: Null<any> = item.data;

        switch (item.type) {
            case 'pass':
                return (
                    <TextField
                        id={item.name}
                        type="password"
                        autoFocus={auto}
                        placeholder={item.hint}
                        defaultValue={item.data}
                        onChange={((event) => {
                            data = event.target.value;
                        })}
                        onKeyUp={(event) => {
                            if ((event.key == 'Enter')) {
                                save(true, data);
                            }
                        }}
                        onBlur={(event) => {
                            save(((data == item.data) == false), data);
                        }}
                        fullWidth />
                );
            case 'list':
                return (
                    <Autocomplete
                        id={item.name}
                        options={item.list}
                        clearIcon={(
                            <SvgIcon sx={{width: '18px', height: '18px'}}>
                                <g
                                    strokeLinejoin="round"
                                    strokeLinecap="round"
                                    strokeWidth="2">
                                    <path d="M12 2c5.523 0 10 4.477 10 10s-4.477 10-10 10S2 17.523 2 12S6.477 2 12 2m3.6 5.2a1 1 0 0 0-1.4.2L12 10.333L9.8 7.4a1 1 0 1 0-1.6 1.2l2.55 3.4l-2.55 3.4a1 1 0 1 0 1.6 1.2l2.2-2.933l2.2 2.933a1 1 0 0 0 1.6-1.2L13.25 12l2.55-3.4a1 1 0 0 0-.2-1.4" />
                                </g>
                            </SvgIcon>
                        )}
                        getOptionLabel={(option: any) => option.text}
                        renderInput={(params) => (
                            <TextField {...params} />
                        )}
                        onChange={((event, data) => {
                            save(((data?.item == item.data) == false), data?.item ?? null);
                        })}
                        fullWidth />
                );
            default:
                return (
                    <TextField
                        id={item.name}
                        autoFocus={auto}
                        placeholder={item.hint}
                        defaultValue={item.data}
                        inputProps={{...(item.case && {style: {textTransform: 'uppercase'}})}}
                        onChange={((event) => {
                            data = item.case ? event.target.value.toUpperCase() : event.target.value;

                            if ((((item.rule ?? null) == null) || item.rule.test(data))) {
                                if (live) {
                                    if (time) {
                                        clearTimeout(time);
                                    }
    
                                    setTime(setTimeout(() => {
                                        save(false, data);
                                    }, 600));
                                }
                            }
                        })}
                        onKeyUp={(event) => {
                            if ((event.key == 'Enter')) {
                                if ((((item.rule ?? null) == null) || item.rule.test(data))) {
                                    save(((data == item.data) == false), data);
                                }
                            }
                        }}
                        onBlur={(live ? (void null) : (event) => {
                            save((((data == item.data) == false) && (((item.rule ?? null) == null) || item.rule.test(data))), data);
                        })}
                        fullWidth />
                );

        }
    }

    return (
        <Card
            sx={{pt: 2, flex: 1, display: 'flex'}}
            variant="outlined">
            <Grid
                direction="column"
                display="flex"
                spacing={1}
                flex={1}
                container>
                {(Boolean(menu.length) && (
                    <Grid item>
                        <Toolbar>
                            <Typography variant="h3">
                                {properties.name}
                            </Typography>
                            <Box sx={{flexGrow: 1}} />
                            <Stack
                                direction="row"
                                spacing={1}>
                                {menu.map((knob: Knob) => (
                                    ((knob.view instanceof Function) ? knob.view(Boolean(properties.wait)) : (
                                        <Tooltip title={knob.hint}>
                                            {(Boolean(knob.text) ? (
                                                <Button
                                                    color="primary"
                                                    variant="contained"
                                                    startIcon={(knob.icon && (
                                                        <SvgIcon sx={{width: '24px', height: '24px'}}>
                                                            <g
                                                                strokeLinejoin="round"
                                                                strokeLinecap="round"
                                                                strokeWidth="2"
                                                                stroke="currentColor"
                                                                fill="none">
                                                                {(knob.icon instanceof Array ? knob.icon.map((path) => (<path d={path} />)) : <path d={knob.icon} />)}
                                                            </g>
                                                        </SvgIcon>
                                                    ))}
                                                    onClick={knob.task}>
                                                    {knob.text}
                                                </Button>
                                            ) : (
                                                <IconButton
                                                    color="primary"
                                                    onClick={knob.task}>
                                                    <SvgIcon sx={{width: '24px', height: '24px'}}>
                                                        <g
                                                            strokeLinejoin="round"
                                                            strokeLinecap="round"
                                                            strokeWidth="2"
                                                            stroke="currentColor"
                                                            fill="none">
                                                            {(knob.icon instanceof Array ? knob.icon.map((path) => (<path d={path} />)) : <path d={knob.icon} />)}
                                                        </g>
                                                    </SvgIcon>
                                                </IconButton>
                                            ))}
                                        </Tooltip>
                                    ))
                                ))}
                            </Stack>
                        </Toolbar>
                    </Grid>
                ))}
                
                {(Boolean(properties.pane?.length) && (
                    <Grid
                        sx={{padding: '16px 24px'}}
                        item
                        container>
                        {properties.pane?.map((item, next) => (
                            <Grid
                                sx={{display: 'flex'}}
                                xs={(item.size ? {tiny: 3, half: 6}[item.size] : 'auto')}
                                key={item.text}
                                item>
                                {(Boolean(next) && (
                                    <Divider
                                        sx={{mx: 3}}
                                        orientation="vertical" />
                                ))}
                                <Box sx={{width: 1, display: 'flex', alignItems: 'center', justifyContent: 'space-between'}}>
                                    <Stack>
                                        <Typography
                                            color="text.secondary"
                                            gutterBottom>
                                            {item.text}
                                        </Typography>
                                        <Typography
                                            color="text.primary"
                                            variant="h3">
                                            {item.data.toLocaleString()}
                                        </Typography>
                                    </Stack>

                                    {(Boolean(item.icon) && (
                                        <Avatar
                                            sx={{background: '#EEEDF0'}}
                                            variant="rounded">
                                            <SvgIcon sx={{width: '24px', height: '24px'}}>
                                                <g
                                                    strokeLinejoin="round"
                                                    strokeLinecap="round"
                                                    strokeWidth="2"
                                                    stroke="#64626B"
                                                    fill="none">
                                                    {(item.icon instanceof Array ? item.icon.map((path) => (<path d={path} />)) : <path d={item.icon} />)}
                                                </g>
                                            </SvgIcon>
                                        </Avatar>
                                    ))}
                                </Box>
                            </Grid>
                        ))}
                    </Grid>
                ))}

                {((Boolean(properties.find) || Boolean(properties.type)) &&  (
                    <Grid item>
                        <Stack
                            sx={{padding: '16px 24px'}}
                            spacing={2}
                            direction="row"
                            component="form"
                            autoComplete="off">
                            {(Boolean(properties.take?.list) && (
                                <Select
                                    sx={{minWidth: 68}}
                                    title="Show"
                                    value={Number(form.take)}
                                    disabled={Boolean(properties.wait)}
                                    onChange={((event) => {
                                        setForm((last) => ({
                                            ...last,
                                            take: Number(event.target.value)
                                        }));
                                    })}>
                                    {properties.take?.list?.map((item) => (
                                        <MenuItem
                                            key={item}
                                            value={item}>
                                            {item}
                                        </MenuItem>
                                    ))}
                                </Select>
                            ))}
                            
                            {(Boolean(properties.find) && (
                                <TextField
                                    value={(form.text ?? '')}
                                    disabled={Boolean(properties.wait)}
                                    placeholder={properties.find?.hint}
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
                                                sx={{margin: '0px 0px 0px 8px', padding: '4px', visibility: (Boolean(form.text?.trim()) ? (Boolean(properties.wait) ? 'hidden' : 'visible') : 'hidden')}}
                                                onClick={(event) => {
                                                    setForm((last) => ({...last, text: null}));
                                                }}>
                                                <SvgIcon sx={{width: '18px', height: '18px'}}>
                                                    <path
                                                        strokeLinejoin="round"
                                                        strokeLinecap="round"
                                                        strokeWidth="2"
                                                        fill="currentColor"
                                                        d="M12 2c5.523 0 10 4.477 10 10s-4.477 10-10 10S2 17.523 2 12S6.477 2 12 2m3.6 5.2a1 1 0 0 0-1.4.2L12 10.333L9.8 7.4a1 1 0 1 0-1.6 1.2l2.55 3.4l-2.55 3.4a1 1 0 1 0 1.6 1.2l2.2-2.933l2.2 2.933a1 1 0 0 0 1.6-1.2L13.25 12l2.55-3.4a1 1 0 0 0-.2-1.4" />
                                                </SvgIcon>
                                            </IconButton>
                                        )
                                    }}
                                    onChange={((event) => {
                                        setForm((last) => ({...last, text: event.target.value}));
                                    })}
                                    fullWidth />
                            ))}
                            
                            {(Boolean(properties.type) && (Boolean(properties.type?.load) ? (
                                <Autocomplete
                                    value={(form.type.data ?? form.type.find)}
                                    options={(properties.type?.list ?? [])}
                                    disabled={Boolean(properties.wait)}
                                    noOptionsText={(properties.type?.wait ? (Boolean(form.type.find) ? 'Buscando...' : 'Cargando...') : 'No hay opciones disponibles.')}
                                    getOptionLabel={(item: any) => (item.text ?? item)}
                                    isOptionEqualToValue={(data: any, pick: any) => (data?.item == pick?.item)}
                                    renderOption={(rest, item) => {
                                        return (
                                            <Stack
                                                {...rest}
                                                sx={{alignItems: 'center'}}
                                                gap={1}
                                                component="li"
                                                direction="row">
                                                {(Boolean((item.icon ?? item.none)) && (
                                                    <Avatar src={item.icon}>
                                                        {(item.none ?? item.text[0])}
                                                    </Avatar>
                                                ))}
                                                <Stack
                                                    sx={{overflow: 'hidden'}}
                                                    direction="column">
                                                    <Typography
                                                        color="text.primary"
                                                        noWrap>
                                                        {item.text}
                                                    </Typography>
                                                    {(Boolean(item.hint) && (
                                                        <Typography
                                                            color="text.secondary"
                                                            noWrap>
                                                            {item.hint}
                                                        </Typography>
                                                    ))}
                                                </Stack>
                                            </Stack>
                                        );
                                    }}
                                    renderInput={(rest) => (
                                        <TextField
                                            {...rest}
                                            placeholder={properties?.type?.hint}
                                            InputProps={{
                                                ...rest.InputProps,
                                                startAdornment: (
                                                    <InputAdornment position="start">
                                                        <SvgIcon sx={{width: '24px', height: '24px'}}>
                                                            <path
                                                                strokeLinejoin="round"
                                                                strokeLinecap="round"
                                                                strokeWidth="2"
                                                                stroke="currentColor"
                                                                fill="none"
                                                                d="M11 15a4 4 0 1 0 8 0a4 4 0 1 0-8 0m7.5 3.5L21 21M4 6h16M4 12h4m-4 6h4" />
                                                        </SvgIcon>
                                                    </InputAdornment>
                                                ),
                                                endAdornment: (
                                                    (properties?.type?.wait ? <CircularProgress color="inherit" size={20} /> : rest.InputProps.endAdornment)
                                                )
                                            }}
                                            onChange={(event) => {
                                                const text = event.target.value?.trim();

                                                clearTimeout(time);

                                                setTime(setTimeout(() => {
                                                    if (properties?.type?.load) {
                                                        properties?.type?.load(text);
                                                    }
                                                }, 600));

                                                setForm((last) => ({
                                                    ...last,
                                                    type: {
                                                        ...last.type,
                                                        find: text
                                                    }
                                                }));
                                            }} />
                                    )}
                                    clearIcon={
                                        <SvgIcon sx={{width: '18px', height: '18px'}}>
                                            <path
                                                strokeLinejoin="round"
                                                strokeLinecap="round"
                                                strokeWidth="2"
                                                fill="currentColor"
                                                d="M12 2c5.523 0 10 4.477 10 10s-4.477 10-10 10S2 17.523 2 12S6.477 2 12 2m3.6 5.2a1 1 0 0 0-1.4.2L12 10.333L9.8 7.4a1 1 0 1 0-1.6 1.2l2.55 3.4l-2.55 3.4a1 1 0 1 0 1.6 1.2l2.2-2.933l2.2 2.933a1 1 0 0 0 1.6-1.2L13.25 12l2.55-3.4a1 1 0 0 0-.2-1.4" />
                                        </SvgIcon>
                                    }
                                    onChange={(event, data, mode) => {
                                        setForm((last) => ({
                                            ...last,
                                            type: {
                                                ...last.type,
                                                data: data
                                            }
                                        }));

                                        if ((mode == 'clear')) {
                                            setForm((last) => ({
                                                ...last,
                                                type: {
                                                    ...last.type,
                                                    find: null
                                                }
                                            }));

                                            if (Boolean(form.type.find)) {
                                                if (properties?.type?.load) {
                                                    properties?.type?.load(null);
                                                }
                                            }

                                            if (Boolean(form.type.data)) {
                                                properties?.type?.task(null);
                                            }
                                        } else {
                                            properties?.type?.task(data);
                                        }
                                    }}
                                    onOpen={(event) => {
                                        if ((properties?.type?.list == null)) {
                                            if (properties?.type?.load) {
                                                properties?.type?.load(null);
                                            }
                                        }
                                    }}
                                    fullWidth
                                    blurOnSelect
                                    autoHighlight />
                            ) : (
                                <Select
                                    sx={{minWidth: 280}}
                                    value={(properties.type?.pick)}
                                    disabled={Boolean(properties.wait)}
                                    renderValue={(item: any) => ((properties.type?.list?.find((next: any) => (next.item == item))?.text ?? (Boolean(properties?.type?.hint) && (<Typography color="secondary">{properties?.type?.hint}</Typography>))))}
                                    displayEmpty={true}
                                    onChange={(event) => {
                                        properties?.type?.task(event.target.value);
                                    }}>
                                    {properties.type?.list?.map((menu: any) => (
                                        <MenuItem
                                            key={menu.item}
                                            value={menu.item}>
                                            {menu.text}
                                        </MenuItem>
                                    ))}
                                </Select>
                            )))}

                        
                                {(Boolean(properties.plot?.length) && (
                                    <Button
                                        title="Charts"
                                        color={(draw ? 'primary' : 'secondary')}
                                        variant={(draw ? 'contained' : 'outlined')}
                                        disabled={Boolean(properties.wait)}
                                        onClick={(event) => {
                                            setDraw((last) => (last ? false : true));
                                        }}>
                                        <SvgIcon sx={{width: '24px', height: '24px'}}>
                                            <g
                                                strokeLinejoin="round"
                                                strokeLinecap="round"
                                                strokeWidth="2"
                                                stroke={(draw ? '#FFFFFF' : '#64626B')}
                                                fill="none">
                                                <path d="M10 3.2A9 9 0 1 0 20.8 14a1 1 0 0 0-1-1H13a2 2 0 0 1-2-2V4a.9.9 0 0 0-1-.8" />
                                                <path d="M15 3.5A9 9 0 0 1 20.5 9H16a1 1 0 0 1-1-1z"  />
                                            </g>
                                        </SvgIcon>
                                </Button>
                                ))}

                                {(Boolean(Object.keys((properties.sort?.list ?? {})).length) && (
                                    <Menu
                                        icon={(
                                            <Badge
                                                badgeContent={Object.keys(form.sort).length}
                                                color="primary">
                                                <Button
                                                    sx={{margin: 0, padding: '8px 8px', borderRadius: 6}}
                                                    color="secondary"
                                                    title="Sorting"
                                                    variant="outlined"
                                                    disabled={Boolean(properties.wait)}
                                                    onClick={(event) => {
                                                        setForm((last) => ({...last, item: null}));
                                                    }}>
                                                    <SvgIcon sx={{width: '24px', height: '24px'}}>
                                                        <path
                                                            strokeLinejoin="round"
                                                            strokeLinecap="round"
                                                            strokeWidth="2"
                                                            stroke="currentColor"
                                                            fill="none"
                                                            d="m3 9l4-4l4 4M7 5v14m14-4l-4 4l-4-4m4 4V5"/>
                                                    </SvgIcon>
                                                </Button>
                                            </Badge>
                                        )}
                                        hold>
                                        <Grid
                                            sx={{width: '480px', padding: '16px 24px 24px 24px'}}
                                            container>
                                            <Grid
                                                sx={{mb: 2}}
                                                xs={12}
                                                item>
                                                <Typography
                                                    color="text.primary"
                                                    variant="h6"
                                                    gutterBottom>
                                                    {(Boolean(form.item) ? `Sort by ${properties.sort?.list[(form.item ?? 'name')]}` : 'Sorting')}
                                                </Typography>
                                                <Typography
                                                    color="text.secondary"
                                                    gutterBottom>
                                                    Add sorting options to report
                                                </Typography>
                                            </Grid>
                                            {(Boolean(form.item) ? (
                                                <Grid
                                                    xs={12}
                                                    spacing={2}
                                                    item
                                                    container>
                                                    <Grid
                                                        xs={12}
                                                        item>
                                                        <Select
                                                            value={Number(form.down)}
                                                            disabled={Boolean(properties.wait)}
                                                            onChange={((event) => {
                                                                setForm((last) => ({
                                                                    ...last,
                                                                    down: Boolean(event.target.value)
                                                                }));
                                                            })}
                                                            fullWidth>
                                                            <MenuItem value={0}>Ascending</MenuItem>
                                                            <MenuItem value={1}>Descending</MenuItem>
                                                        </Select>
                                                    </Grid>
                                                    <Grid 
                                                        xs={12}
                                                        item>
                                                        <Stack
                                                            justifyContent="end"
                                                            direction="row"
                                                            spacing={2}>
                                                            <Button
                                                                color="secondary"
                                                                variant="contained"
                                                                disabled={Boolean(properties.wait)}
                                                                onClick={(event) => {
                                                                    setForm((last) => ({...last, item: null}));
                                                                }}>
                                                                Cancel
                                                            </Button>
                                                            <Button
                                                                variant="contained"
                                                                disabled={Boolean(properties.wait)}
                                                                onClick={(event) => {
                                                                    setForm((last) => ({
                                                                        ...last,
                                                                        item: null,
                                                                        page: properties.more ? 1 : last.page,
                                                                        sort: {
                                                                            ...(properties.sort?.once ? null : last.sort),
                                                                            [(form.item ?? '[item]')]: form.down}
                                                                        }
                                                                    ));
                                                                }}>
                                                                Apply
                                                            </Button>
                                                        </Stack>
                                                    </Grid>
                                                </Grid>
                                            ) : (
                                                <Grid
                                                    xs={12}
                                                    spacing={2}
                                                    item
                                                    container>
                                                    <Grid
                                                        xs={12}
                                                        item>
                                                        <Autocomplete
                                                            value={null}
                                                            options={Object.keys((properties.sort?.list ?? {})).map((item) => ({name: properties.sort?.list[item], item: item}))}
                                                            disabled={Boolean(properties.wait)}
                                                            blurOnSelect={true}
                                                            getOptionLabel={(item: any) => item.name}
                                                            getOptionDisabled={(data) => (((form.sort[data.item] == (void null)) == false))}
                                                            renderInput={(rest) => (
                                                                <TextField
                                                                    {...rest}
                                                                    placeholder="Add sort" />
                                                            )}
                                                            onChange={((event, data) => {
                                                                if (data) {
                                                                    setForm((last) => ({...last, item: data.item, down: false}));
                                                                }
                                                            })}
                                                            fullWidth />
                                                    </Grid>
                                                
                                                    {(Boolean(Object.keys(form.sort).length) ? Object.keys(form.sort).map((item) => (
                                                        <Grid
                                                            xs={12}
                                                            key={item}
                                                            item>
                                                            <Paper
                                                                sx={{
                                                                    padding: '8px 16px',
                                                                    display: 'flex',
                                                                    alignItems: 'center',
                                                                    justifyContent: 'space-between',
                                                                    '&:hover': (theme) => (Boolean(properties.wait) ? {} : {
                                                                        cursor: 'pointer',
                                                                        borderColor: 'primary.main'
                                                                    })
                                                                }}
                                                                variant="outlined"
                                                                component={Link}
                                                                onClick={(event) => {
                                                                    setForm((last) => ({...last, item: item, down: form.sort[item]}));
                                                                }}>
                                                                <Typography
                                                                    sx={{mb: 0, flex: 1}}
                                                                    gutterBottom>
                                                                    {properties.sort?.list[item]}
                                                                </Typography>
                                                                <SvgIcon sx={{mr: 1, width: '24px', height: '24px'}}>
                                                                    <path
                                                                        strokeLinejoin="round"
                                                                        strokeLinecap="round"
                                                                        strokeWidth="2"
                                                                        stroke="currentColor"
                                                                        fill="none"
                                                                        d={(form.sort[item] ? 'M4 6h9m-9 6h7m-7 6h7m4-3l3 3l3-3m-3-9v12' : 'M4 6h7m-7 6h7m-7 6h9m2-9l3-3l3 3m-3-3v12')} />
                                                                </SvgIcon>
                                                                <IconButton
                                                                    sx={{margin: 0}}
                                                                    disabled={Boolean(properties.wait)}
                                                                    onClick={(event) => {
                                                                        event.stopPropagation();

                                                                        event.preventDefault();

                                                                        delete form.sort[item];

                                                                        setForm((last) => ({...last, page: properties.more ? 1 : last.page, sort: {...form.sort}}));
                                                                    }}>
                                                                    <SvgIcon sx={{width: '24px', height: '24px'}}>
                                                                        <path
                                                                            strokeLinejoin="round"
                                                                            strokeLinecap="round"
                                                                            strokeWidth="2"
                                                                            stroke="currentColor"
                                                                            fill="none"
                                                                            d="M4 7h16m-10 4v6m4-6v6M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2l1-12M9 7V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v3" />
                                                                    </SvgIcon>
                                                                </IconButton>
                                                            </Paper>
                                                        </Grid>
                                                    )) : (
                                                        <Grid
                                                            xs={12}
                                                            item>
                                                            <Box sx={{mt: 4, mb: 2, display: 'flex', flexDirection: 'column', alignItems: 'center'}}>
                                                                <Avatar sx={{mb: 2, width: 96, height: 96, color: '#B3B3B3', background: '#F3F3F3'}}>
                                                                    <NoneIcon sx={{transform: 'scale(2)'}} />
                                                                </Avatar>
                                                                <Typography
                                                                    color="text.primary"
                                                                    gutterBottom>
                                                                    No sort options
                                                                </Typography>
                                                                <Typography
                                                                    color="text.secondary"
                                                                    gutterBottom>
                                                                    There is no sorting options in this report.
                                                                </Typography>
                                                            </Box>
                                                        </Grid>
                                                    ))}
                                                </Grid>
                                            ))}
                                        </Grid>
                                    </Menu>
                                ))}

                                {(Boolean(Object.keys((properties.pipe?.list ?? {})).length) && (
                                    <Menu
                                        icon={(
                                            <Badge
                                                badgeContent={Object.keys(form.pipe).length}
                                                color="primary">
                                                <Button
                                                    sx={{margin: 0, padding: '8px 8px', borderRadius: 6}}
                                                    color="secondary"
                                                    title="Filters"
                                                    variant="outlined"
                                                    disabled={Boolean(properties.wait)}
                                                    onClick={(event) => {
                                                        setForm((last) => ({...last, item: null, menu: null, pick: null, span: false}));
                                                    }}>
                                                    <SvgIcon sx={{width: '24px', height: '24px'}}>
                                                        <path
                                                            strokeLinejoin="round"
                                                            strokeLinecap="round"
                                                            strokeWidth="2"
                                                            stroke="currentColor"
                                                            fill="none"
                                                            d="M4 4h16v2.172a2 2 0 0 1-.586 1.414L15 12v7l-6 2v-8.5L4.52 7.572A2 2 0 0 1 4 6.227z" />
                                                    </SvgIcon>
                                                </Button>
                                            </Badge>
                                        )}
                                        hold>
                                        <Grid
                                            sx={{width: '480px', padding: '16px 24px 24px 24px'}}
                                            container>
                                            <Grid
                                                sx={{mb: 2}}
                                                xs={12}
                                                item>
                                                <Typography
                                                    color="text.primary"
                                                    variant="h6"
                                                    gutterBottom>
                                                    {(Boolean(form.pick) ? `Filter by ${form.pick?.name}` : 'Filters')}
                                                </Typography>
                                                <Typography
                                                    color="text.secondary"
                                                    gutterBottom>
                                                    Add filters to report
                                                </Typography>
                                            </Grid>
                                            {(Boolean(form.pick) ? (
                                                <Grid
                                                    xs={12}
                                                    spacing={2}
                                                    item
                                                    container>
                                                    {(Boolean(form.pick?.menu) && (
                                                        <Grid
                                                            xs={12}
                                                            item>
                                                            <Select
                                                                value={(form.pick?.menu?.list.find((next: any) => (next.item == form.menu)) ?? null)}
                                                                disabled={Boolean(properties.wait)}
                                                                onChange={((event) => {
                                                                    let sign = form.pick?.sign.list.find((data: any) => (data.item == form.pick?.sign.pick));

                                                                    setForm((last) => ({
                                                                        ...last,
                                                                        menu: event.target.value.item,
                                                                        span: sign?.span ?? false,
                                                                        sign: form.pick?.sign.pick,
                                                                        data: event.target.value.item ?? (sign?.span ? ((form.pick?.type == 'date') ? [dayjs(new Date()).format('YYYY-MM-DD'), dayjs(new Date()).format('YYYY-MM-DD')] : [null, null]) : ((form.pick?.type == 'date') ? dayjs(new Date()).format('YYYY-MM-DD') : null))
                                                                    }));
                                                                })}
                                                                fullWidth>
                                                                {form.pick?.menu?.list.map((menu: any) => (
                                                                    <MenuItem
                                                                        key={menu.item}
                                                                        value={menu}>
                                                                        {menu.text}
                                                                    </MenuItem>
                                                                ))}
                                                            </Select>
                                                        </Grid>
                                                    ))}

                                                    {(((Boolean(form.pick?.menu) == false) || (Boolean(form.menu) == false)) && (
                                                        <Grid
                                                            xs={12}
                                                            item>
                                                            <Select
                                                                value={(form.pick?.sign.list.find((next: any) => (next.item == form.sign)) ?? null)}
                                                                disabled={Boolean(properties.wait)}
                                                                onChange={((event) => {
                                                                    setForm((last) => ({
                                                                        ...last,
                                                                        sign: event.target.value.item,
                                                                        span: event.target.value.span,
                                                                        data: (event.target.value.span ? (last.span ? last.data : ((last.pick?.type == 'menu') ? [last.data] : [last.data, last.data])) : (last.span ? last.data[0] : last.data))
                                                                    }));
                                                                })}
                                                                fullWidth>
                                                                {form.pick?.sign.list.map((sign: any) => (
                                                                    <MenuItem
                                                                        key={sign.item}
                                                                        value={sign}>
                                                                        {sign.text}
                                                                    </MenuItem>
                                                                ))}
                                                            </Select>
                                                        </Grid>
                                                    ))}

                                                    {(((Boolean(form.pick?.menu) == false) || (Boolean(form.menu) == false)) && (
                                                        <Grid
                                                            xs={12}
                                                            item>
                                                            {((type, item, data) => {
                                                                switch (type) {
                                                                    case 'date':
                                                                        return (
                                                                            <LocalizationProvider dateAdapter={AdapterDayjs}>
                                                                                <DatePicker
                                                                                    value={dayjs((form.span ? form.data[0] : form.data))}
                                                                                    disabled={Boolean(properties.wait)}
                                                                                    slotProps={{
                                                                                        textField: {
                                                                                            fullWidth: true,
                                                                                            placeholder: form.pick?.hint
                                                                                        }
                                                                                    }}
                                                                                    onChange={(date) => {
                                                                                        setForm((last) => ({
                                                                                            ...last,
                                                                                            data: last.span ? [dayjs(date).format('YYYY-MM-DD'), last.data[1]] : dayjs(date).format('YYYY-MM-DD')
                                                                                        }));
                                                                                    }} />
                                                                            </LocalizationProvider>
                                                                        )
                                                                    case 'menu':
                                                                        return (
                                                                            <Select
                                                                                value={form.data}
                                                                                multiple={form.span}
                                                                                disabled={Boolean(properties.wait)}
                                                                                renderValue={(data: any) => ((data instanceof Array) ? data?.map((item: any) => (form.pick?.data.find((menu: any) => (menu.item == item))?.text ?? item)).join(', ') : (form.pick?.data.find((menu: any) => (menu.item == data))?.text ?? data))}
                                                                                onChange={((event) => {
                                                                                    setForm((last) => ({
                                                                                        ...last,
                                                                                        data: event.target.value
                                                                                    }));
                                                                                })}
                                                                                fullWidth>
                                                                                {form.pick?.data?.map((menu: {item: string | number, text: string}) => (
                                                                                    <MenuItem
                                                                                        key={menu.item}
                                                                                        value={menu.item}>
                                                                                        {(form.span && (
                                                                                            <Checkbox
                                                                                                sx={{margin: '0px', padding: '0px'}}
                                                                                                checked={Boolean(form.data?.find((item: string | number) => (menu.item == item)))} />
                                                                                        ))}
                                                                                        {menu.text}
                                                                                    </MenuItem>
                                                                                ))}
                                                                            </Select>
                                                                        )
                                                                    case 'list':
                                                                        return (
                                                                            <Autocomplete
                                                                                value={(properties.pipe?.list[(item ?? '')]?.data?.find((next: any) => (next.item == data)) ?? null)}
                                                                                options={(form.pick?.data ?? [])}
                                                                                disabled={Boolean(properties.wait)}
                                                                                getOptionLabel={(data: any) => data.text}
                                                                                renderInput={(rest) => (
                                                                                    <TextField
                                                                                        {...rest}
                                                                                        placeholder={form.pick?.hint} />
                                                                                )}
                                                                                onChange={((event, data: any) => {
                                                                                    setForm((last) => ({...last, data: data.item}));
                                                                                })}
                                                                                fullWidth
                                                                                disableClearable />
                                                                        )
                                                                    default:
                                                                        return (Boolean(form.pick?.mask) ? (
                                                                            <TextField
                                                                                value={((form.span ? form.data[0] : form.data) ?? '')}
                                                                                disabled={Boolean(properties.wait)}
                                                                                placeholder={form.pick?.hint}
                                                                                onChange={((event) => {
                                                                                    setForm((last) => ({
                                                                                        ...last,
                                                                                        data: last.span ? [event.target.value, last.data[1]] : event.target.value
                                                                                    }));
                                                                                })}
                                                                                
                                                                                fullWidth>
                                                                                    <InputMask
                                                                                mask={form.pick?.mask}
                                                                                />
                                                                                </TextField>
                                                                        ) : (
                                                                            <TextField
                                                                                value={((form.span ? form.data[0] : form.data) ?? '')}
                                                                                disabled={Boolean(properties.wait)}
                                                                                placeholder={form.pick?.hint}
                                                                                onChange={((event) => {
                                                                                    setForm((last) => ({
                                                                                        ...last,
                                                                                        data: last.span ? [event.target.value, last.data[1]] : event.target.value
                                                                                    }));
                                                                                })}
                                                                                fullWidth />
                                                                        ))
                                                                }
                                                            })(form.pick?.type, form.item, form.data)}
                                                        </Grid>
                                                    ))}

                                                    {((((Boolean(form.pick?.menu) == false) || (Boolean(form.menu) == false)) && ((form.pick?.type == 'menu') == false) && form.span) && (
                                                        <Grid
                                                            xs={12}
                                                            item>
                                                            {((type, item, data) => {
                                                                switch (type) {
                                                                    case 'date':
                                                                        return (
                                                                            <LocalizationProvider dateAdapter={AdapterDayjs}>
                                                                                <DatePicker
                                                                                    value={dayjs(form.data[1])}
                                                                                    disabled={Boolean(properties.wait)}
                                                                                    slotProps={{
                                                                                        textField: {
                                                                                            fullWidth: true,
                                                                                            placeholder: form.pick?.hint
                                                                                        }
                                                                                    }}
                                                                                    onChange={(date) => {
                                                                                        setForm((last) => ({...last, data: [last.data[0], dayjs(date).format('YYYY-MM-DD')]}));
                                                                                    }} />
                                                                            </LocalizationProvider>
                                                                        )
                                                                    default:
                                                                        return (
                                                                            <TextField
                                                                                value={(form.data[1] ?? '')}
                                                                                disabled={Boolean(properties.wait)}
                                                                                placeholder={form.pick?.hint}
                                                                                onChange={((event) => {
                                                                                    setForm((last) => ({...last, data: [last.data[0], event.target.value]}));
                                                                                })}
                                                                                fullWidth />
                                                                        )
                                                                }
                                                            })(form.pick?.type, form.item, form.data)}
                                                        </Grid>
                                                    ))}

                                                    <Grid 
                                                        xs={12}
                                                        item>
                                                        <Stack
                                                            justifyContent="end"
                                                            direction="row"
                                                            spacing={2}>
                                                            <Button
                                                                color="secondary"
                                                                variant="contained"
                                                                disabled={Boolean(properties.wait)}
                                                                onClick={(event) => {
                                                                    setForm((last) => ({...last, pick: null}));
                                                                }}>
                                                                Cancel
                                                            </Button>
                                                            <Button
                                                                variant="contained"
                                                                disabled={(Boolean(properties.wait) || ((Boolean(form.menu) || (Boolean(form.pick?.sign.list.find((next: any) => (next.item == form.sign))) && (form.span ? (Boolean(form.data[0]?.toString().trim()) && Boolean(form.data[1]?.toString().trim())) : Boolean(form.data?.toString().trim())))) == false))}
                                                                onClick={(event) => {
                                                                    if (form.pipe[(form.item ?? '')]) {
                                                                        setForm((last) => ({...last, pick: null, pipe: {...form.pipe, [(form.item ?? '')]: {
                                                                            data: form.menu ?? form.data,
                                                                            sign: form.sign
                                                                        }}}));
                                                                    } else {
                                                                        setForm((last) => ({...last, pick: null, pipe: {[(form.item ?? '')]: {
                                                                            data: form.menu ?? form.data,
                                                                            sign: form.sign
                                                                        }, ...form.pipe}}));
                                                                    }
                                                                }}>
                                                                Apply
                                                            </Button>
                                                        </Stack>
                                                    </Grid>
                                                </Grid>
                                            ) : (
                                                <Grid
                                                    xs={12}
                                                    spacing={2}
                                                    item
                                                    container>
                                                    <Grid
                                                        xs={12}
                                                        item>
                                                        <Autocomplete
                                                            value={null}
                                                            options={Object.keys((properties.pipe?.list ?? {})).map((item) => ({
                                                                item: item,
                                                                name: properties.pipe?.list[item].name,
                                                                sign: properties.pipe?.list[item].sign.pick,
                                                                menu: properties.pipe?.list[item].menu?.pick,
                                                                span: properties.pipe?.list[item].sign.list.find((next) => (next.item == properties.pipe?.list[item].sign.pick))?.span ?? false
                                                            }))}
                                                            disabled={Boolean(properties.wait)}
                                                            blurOnSelect={true}
                                                            getOptionLabel={(item: any) => item.name}
                                                            getOptionDisabled={(data) => (Boolean(form.pipe[data.item]))}
                                                            renderInput={(rest) => (
                                                                <TextField
                                                                    {...rest}
                                                                    placeholder="Add filter" />
                                                            )}
                                                            onChange={((event, data) => {
                                                                if (data) {
                                                                    setForm((last) => ({
                                                                        ...last,
                                                                        item: data.item,
                                                                        sign: data.sign,
                                                                        menu: data.menu,
                                                                        span: data.span,
                                                                        pick: properties.pipe?.list[data.item],
                                                                        data: properties.pipe?.list[data.item].menu ? properties.pipe?.list[data.item].menu?.pick : ((type, span) => {
                                                                        switch (type) {
                                                                            case 'date':
                                                                                return (span ? [dayjs(new Date()).format('YYYY-MM-DD'), dayjs(new Date()).format('YYYY-MM-DD')] : dayjs(new Date()).format('YYYY-MM-DD'));
                                                                            case 'menu':
                                                                                return [];

                                                                        }

                                                                        return (span ? [null, null] : null);
                                                                    })(properties.pipe?.list[data.item].type, data.span)}));
                                                                }
                                                            })}
                                                            fullWidth />
                                                    </Grid>
                                                
                                                    {(Boolean(Object.keys(form.pipe).length) ? Object.keys(form.pipe).map((item) => (
                                                        <Grid
                                                            xs={12}
                                                            key={item}
                                                            item>
                                                            <Paper
                                                                sx={{
                                                                    padding: '8px 16px',
                                                                    display: 'flex',
                                                                    alignItems: 'center',
                                                                    justifyContent: 'space-between',
                                                                    '&:hover': (theme) => (Boolean(properties.wait) ? {} : {
                                                                        cursor: 'pointer',
                                                                        borderColor: 'primary.main'
                                                                    })
                                                                }}
                                                                variant="outlined"
                                                                component={Link}
                                                                onClick={(event) => {
                                                                    let sign = properties.pipe?.list[item].sign.list.find((data: any) => (data.item == form.pipe[item].sign));

                                                                    let menu = properties.pipe?.list[item].menu?.list.find((data: any) => (data.item == form.pipe[item].data));

                                                                    setForm((last) => ({
                                                                        ...last,
                                                                        item: item,
                                                                        menu: menu?.item,
                                                                        sign: sign?.item,
                                                                        span: sign?.span ?? false,
                                                                        data: form.pipe[item].data,
                                                                        pick: properties.pipe?.list[item]
                                                                    }));
                                                                }}>
                                                                <Stack>
                                                                    <Typography
                                                                        sx={{mb: 0}}
                                                                        gutterBottom>
                                                                        {properties.pipe?.list[item].name}
                                                                    </Typography>
                                                                    <Typography
                                                                        sx={{mb: 0, color: 'primary.main'}}
                                                                        gutterBottom>
                                                                        {((type, list, menu, date, data) => {
                                                                            let item = menu?.find((some) => (some.item === data));

                                                                            if (item) {
                                                                                return item.text;
                                                                            } else {
                                                                                switch (type) {
                                                                                    case 'list':
                                                                                        return (list.find((next: any) => (next.item == data))?.text ?? data);
                                                                                    case 'date':
                                                                                        return ((data instanceof Array) ? `${dayjs(data[0]).format((date ?? 'MM/DD/YYYY'))} ~ ${dayjs(data[1]).format((date ?? 'MM/DD/YYYY'))}` : dayjs(data).format((date ?? 'MM/DD/YYYY')));
                                                                                    case 'menu':
                                                                                        return ((data instanceof Array) ? data.map((item: any) => (list.find((menu: any) => (menu.item == item))?.text ?? item)).join(', ') : (list.find((next: any) => (next.item == data))?.text ?? data));
                                                                                }
            
                                                                                return (data instanceof Array ? `${data[0]} ~ ${data[1]}` : data);
                                                                            }
                                                                        })(properties.pipe?.list[item].type, properties.pipe?.list[item].data, properties.pipe?.list[item].menu?.list, properties.pipe?.list[item].date, form.pipe[item].data)}
                                                                    </Typography>
                                                                </Stack>
                                                                <IconButton
                                                                    sx={{margin: 0}}
                                                                    disabled={Boolean(properties.wait)}
                                                                    onClick={(event) => {
                                                                        event.stopPropagation();

                                                                        event.preventDefault();

                                                                        delete form.pipe[item];

                                                                        setForm((last) => ({...last, pipe: {...form.pipe}}));
                                                                    }}>
                                                                    <SvgIcon sx={{width: '24px', height: '24px'}}>
                                                                        <path
                                                                            strokeLinejoin="round"
                                                                            strokeLinecap="round"
                                                                            strokeWidth="2"
                                                                            stroke="currentColor"
                                                                            fill="none"
                                                                            d="M4 7h16m-10 4v6m4-6v6M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2l1-12M9 7V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v3" />
                                                                    </SvgIcon>
                                                                </IconButton>
                                                            </Paper>
                                                        </Grid>
                                                    )) : (
                                                        <Grid
                                                            xs={12}
                                                            item>
                                                            <Box sx={{mt: 4, mb: 2, display: 'flex', flexDirection: 'column', alignItems: 'center'}}>
                                                                <Avatar sx={{mb: 2, width: 96, height: 96, color: '#B3B3B3', background: '#F3F3F3'}}>
                                                                    <NoneIcon sx={{transform: 'scale(2)'}} />
                                                                </Avatar>
                                                                <Typography
                                                                    sx={{color: '#939393', fontSize: 18, fontWeight: 700}}
                                                                    gutterBottom>
                                                                    No filters
                                                                </Typography>
                                                                <Typography
                                                                    sx={{color: '#737373', fontSize: 14, fontWeight: 300}}
                                                                    gutterBottom>
                                                                    There is no filters in this report.
                                                                </Typography>
                                                            </Box>
                                                        </Grid>
                                                    ))}
                                                </Grid>
                                            ))}
                                        </Grid>
                                    </Menu>
                                ))}

                                {(Boolean(Object.keys((properties.pipe?.list ?? properties.sort?.list ?? {})).length) && (
                                    <Button
                                        sx={{margin: 0, padding: '8px 8px', borderRadius: 6}}
                                        color="secondary"
                                        title="Refresh"
                                        variant="outlined"
                                        disabled={Boolean(properties.wait)}
                                        onClick={(event) => {
                                            setForm((last) => ({...last, sort: {}, pipe: {}, text: null, page: properties.more ? 1 : last.page}));
                                        }}>
                                        <SvgIcon sx={{width: '24px', height: '24px'}}>
                                            <path
                                                strokeLinejoin="round"
                                                strokeLinecap="round"
                                                strokeWidth="2"
                                                stroke="currentColor"
                                                fill="none"
                                                d="M20 11A8.1 8.1 0 0 0 4.5 9M4 5v4h4m-4 4a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" />
                                        </SvgIcon>
                                    </Button>
                                ))}

                                {(Boolean(properties.mode) && (
                                    <ToggleButtonGroup
                                        color="primary"
                                        value={card}
                                        onChange={(event: React.MouseEvent<HTMLElement>, card: boolean) => {
                                            setCard(card);
                                        }}
                                        exclusive>
                                        <ToggleButton value={false}>
                                            <SvgIcon sx={{width: '24px', height: '24px'}}>
                                                <path
                                                    strokeLinejoin="round"
                                                    strokeLinecap="round"
                                                    strokeWidth="2"
                                                    stroke="currentColor"
                                                    fill="none"
                                                    d="M3 5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2zm0 5h18M10 3v18" />
                                            </SvgIcon>
                                        </ToggleButton>
                                        <ToggleButton value={true}>
                                            <SvgIcon sx={{width: '24px', height: '24px'}}>
                                                <path
                                                    strokeLinejoin="round"
                                                    strokeLinecap="round"
                                                    strokeWidth="2"
                                                    stroke="currentColor"
                                                    fill="none"
                                                    d="M4 5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1zm10 0a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1zM4 15a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1zm10 0a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1z" />
                                            </SvgIcon>
                                        </ToggleButton>
                                    </ToggleButtonGroup>
                                ))}
                        </Stack>
                    </Grid>
                ))}

                <Grid item>
                    {((Boolean(properties.menu?.length) || Boolean(properties.pipe?.list.length)) && (
                        <Divider />
                    ))}
                </Grid>

                {(draw && (
                    <Grid
                        sx={{padding: '16px 24px'}}
                        spacing={2}
                        xs={12}
                        item
                        container>
                        {properties.plot?.map((item: Hash<any>) => (
                            <Grid
                                xs={12}
                                key={item.name}
                                item>
                                <Card variant="outlined">
                                    <CardHeader
                                        title={item.name}
                                        subheader={item.hint} />
                                    <CardContent>
                                        {item.show ? (Boolean(item.series.some((item: any) => (item.data?.some((item: number) => (Boolean(item))) ?? Boolean(item)))) ? (
                                            <Chart
                                                options={item.options}
                                                series={item.series}
                                                height={item.size}
                                                type={item.type} />
                                        ) : (
                                            <Box sx={{mt: 4, mb: 2, display: 'flex', flexDirection: 'column', alignItems: 'center'}}>
                                                <Avatar sx={{mb: 2, width: 96, height: 96, color: '#B3B3B3', background: '#F3F3F3'}}>
                                                    <NoneIcon sx={{transform: 'scale(2)'}} />
                                                </Avatar>
                                                <Typography
                                                    sx={{color: '#939393', fontSize: 18, fontWeight: 700}}
                                                    gutterBottom>
                                                    No records
                                                </Typography>
                                                <Typography
                                                    sx={{color: '#737373', fontSize: 14, fontWeight: 300}}
                                                    gutterBottom>
                                                    No available records found.
                                                </Typography>
                                            </Box>
                                        )) : (
                                            <Box
                                                justifyContent="center"
                                                alignItems="center"
                                                display="flex"
                                                height={280}>
                                                <CircularProgress size={48} />
                                            </Box>
                                        )}
                                    </CardContent>
                                </Card>
                            </Grid>
                        ))}
                    </Grid>
                ))}
            
            
                {((Boolean(properties.view) && card) ? (
                    <Grid
                        display="flex"
                        flex={1}
                        sx={{mt: 2, ...(properties.flex && {width: '100%', overflow: 'auto', position: 'relative'})}}
                        item>
                        {(Boolean(properties.list?.length) ? properties.list?.map((item: Item, next: any) => (
                            <Grid
                                key={item[properties.seek]}
                                xs={4}
                                item>
                                {properties.view!(item)}
                            </Grid>
                        )) : (
                            <Grid xs={12} item>
                                {(Boolean(properties.wait) ? (
                                    <Box
                                        sx={{mt: 4, mb: 2}}
                                        display="flex"
                                        alignItems="center"
                                        justifyContent="center">
                                        <CircularProgress size={48} />
                                    </Box>
                                ) : (
                                    <Box sx={{mt: 4, mb: 2, display: 'flex', flexDirection: 'column', alignItems: 'center'}}>
                                        <Avatar sx={{mb: 2, width: 96, height: 96, color: '#B3B3B3', background: '#F3F3F3'}}>
                                            <NoneIcon sx={{transform: 'scale(2)'}} />
                                        </Avatar>
                                        <Typography
                                            sx={{color: '#939393', fontSize: 18, fontWeight: 700}}
                                            gutterBottom>
                                            {(properties.none?.text ?? 'No records')}
                                        </Typography>
                                        <Typography
                                            sx={{color: '#737373', fontSize: 14, fontWeight: 300}}
                                            gutterBottom>
                                            {(properties.none?.note ?? 'No available records found.')}
                                        </Typography>
                                    </Box>
                                ))}
                            </Grid>
                        ))}
                    </Grid>
                ) : (
                    <TableContainer sx={{flex: 1, overflowX: 'initial'}}>
                        <Table stickyHeader>
                            <TableHead>
                                <TableRow>
                                    {(properties.bulk && (
                                        <TableCell padding="checkbox">
                                            <Checkbox
                                                onChange={(event) => {
                                                }}/>
                                        </TableCell>
                                    ))}

                                    {properties.data.filter((item) => (item.show)).map((head) => (
                                        <TableCell
                                            sx={{...(head.sort && {
                                                cursor: 'pointer'
                                            })}}
                                            key={head.item}
                                            width={head.size}
                                            onClick={(event) => {
                                                if (head.sort) {
                                                    setForm((last) => ({
                                                        ...last,
                                                        page: properties.more ? 1 : last.page,
                                                        sort: {
                                                            ...(properties.sort?.once ? null : last.sort),
                                                            [head.item]: form.sort[head.item] ? false : true
                                                        }
                                                    }));
                                                }
                                            }}>
                                            <Stack
                                                gap={1}
                                                direction="row"
                                                justifyContent={(head.edge ?? 'start')}
                                                alignItems="center">
                                                {(head.sort && (
                                                    <SvgIcon sx={{width: '18px', height: '18px'}}>
                                                        <path
                                                            strokeLinecap="round"
                                                            strokeLinejoin="round"
                                                            strokeWidth="2"
                                                            stroke="currentColor"
                                                            fill="none"
                                                            d={((head.item in form.sort) ? (form.sort[head.item] ? 'M4 6h9m-9 6h7m-7 6h7m4-3l3 3l3-3m-3-9v12' : 'M4 6h7m-7 6h7m-7 6h9m2-9l3-3l3 3m-3-3v12') : 'm3 9l4-4l4 4M7 5v14m14-4l-4 4l-4-4m4 4V5')} />
                                                    </SvgIcon>
                                                ))}
                                                <Typography variant="inherit">
                                                    {head.name}
                                                </Typography>
                                            </Stack>
                                        </TableCell>
                                    ))}

                                    {(Boolean(dash.length) && (
                                        <TableCell width={((dash.length * 40))}>
                                            {dash.filter((item) => (item.bulk)).map((knob, icon: any) => (
                                                <Tooltip title={knob.hint}>
                                                    <IconButton
                                                        sx={{margin: 0}}
                                                        title={knob.hint}
                                                        disabled={Boolean(properties.wait)}
                                                        onClick={(event) => {

                                                        }}>
                                                        <Avatar
                                                            sx={(theme) => ({
                                                                width: 32,
                                                                height: 32,
                                                                background: theme.palette.divider
                                                            })}
                                                            variant="circular">
                                                            <SvgIcon sx={{width: '24px', height: '24px'}}>
                                                                <g
                                                                    strokeLinejoin="round"
                                                                    strokeLinecap="round"
                                                                    strokeWidth="2"
                                                                    fill="none">
                                                                    {((icon = (knob.icon instanceof Function) ? knob.icon(null) : knob.icon) instanceof Array ? icon.map((path) => (<path d={path} />)) : <path d={icon} />)}
                                                                </g>
                                                            </SvgIcon>
                                                        </Avatar>
                                                    </IconButton>
                                                </Tooltip>
                                            ))}
                                        </TableCell>
                                    ))}
                                </TableRow>
                            </TableHead>
                            <TableBody>
                                {(Boolean(list?.length) ? list?.map((item: any, next: any) => (
                                    <Fragment>
                                        {(Boolean(item.type) && (
                                            <TableRow key={next}>
                                                <TableCell colSpan={(properties.data.length + (properties.bulk ? 1 : 0) + (dash.length ? 1 : 0))}>
                                                    {(properties.view ? properties.view(item.type, item.data.length) : `${Object.keys(item.type).map((name) => (`${name}: ${item.type[name]}`)).join(', ')}`)}
                                                </TableCell>
                                            </TableRow>
                                        ))}

                                        {item.data.map((item: any, next: any) => (
                                            <TableRow
                                                sx={(properties.tint && {background: properties.tint(item, next)})}
                                                key={item[properties.seek]}
                                                hover>
                                                {(properties.bulk && (
                                                    <TableCell padding="checkbox">
                                                        <Checkbox
                                                            value="true" />
                                                    </TableCell>
                                                ))}
                                                
                                                {properties.data.filter((item) => (item.show)).map((head) => (
                                                    <TableCell
                                                        sx={{color: ((head.text && head.text(item, next)) ?? 'inherit'), background: ((head.back && head.back(item, next)) ?? 'inherit'), ...(head.wrap && {overflow: 'hidden', maxWidth: 0, whiteSpace: 'nowrap', textOverflow: 'ellipsis'})}}
                                                        key={head.item}
                                                        align={(head.edge ?? 'inherit')}
                                                        title={head.cast ? head.cast(item) : (head.pick ? ((next = head.pick.find((pick) => ((item[head.item] == pick.item))))?.text ?? 'N/A') : item[head.item])}
                                                        onDoubleClick={() => {
                                                            if (head.save) {
                                                                setMark({
                                                                    head: head,
                                                                    item: item
                                                                });
                                                            } else {
                                                                if (properties.pick) {
                                                                    properties.pick(item, head.name);
                                                                }
                                                            }
                                                        }}>
                                                        {(head.cast ? head.cast(item) : (head.pick ? (head.save ? (
                                                            <Menu
                                                                icon={(
                                                                    <Badge
                                                                        variant="dot"
                                                                        overlap="circular"
                                                                        anchorOrigin={{vertical: 'top', horizontal: 'left'}}
                                                                        sx={{
                                                                            '& .MuiBadge-dot': {
                                                                                top: 11,
                                                                                left: -11,
                                                                                width: 8,
                                                                                height: 8,
                                                                                background: `#${(next?.tint ?? '9E9E9E')}`
                                                                            },
                                                                            cursor: 'pointer'
                                                                        }}>
                                                                        {(next?.text ?? 'N/A')}
                                                                    </Badge>
                                                                )}
                                                                find="Buscar opción">
                                                                {(text: Null<string>) => {
                                                                    let list = (Boolean(text) ? head?.pick?.filter((item) => item.text.toLowerCase().includes((text ?? '').toLowerCase())) : head?.pick) ?? [];

                                                                    return (Boolean(list.length) ? (
                                                                        <MenuList sx={{width: '320px'}}>
                                                                            {list.map((menu) => (
                                                                                <MenuItem
                                                                                    sx={{gap: '12px', px: '32px'}}
                                                                                    key={menu.item}
                                                                                    selected={(menu.item == item[head.item])}
                                                                                    onClick={() => {
                                                                                        if (head.save) {
                                                                                            head.save(item, menu);
                                                                                        }
                                                                                    }}>
                                                                                    <Badge
                                                                                        variant="dot"
                                                                                        overlap="circular"
                                                                                        anchorOrigin={{vertical: 'top', horizontal: 'left'}}
                                                                                        sx={{
                                                                                            cursor: 'pointer',
                                                                                            '& .MuiBadge-dot': {
                                                                                                top: 11,
                                                                                                left: -11,
                                                                                                width: 8,
                                                                                                height: 8,
                                                                                                background: `#${menu.tint}`
                                                                                            }
                                                                                        }}>
                                                                                        {menu.text}
                                                                                    </Badge>
                                                                                </MenuItem>
                                                                            ))}
                                                                        </MenuList>
                                                                    ) : (
                                                                        <Box sx={{mt: 4, mb: 2, width: '320px', display: 'flex', flexDirection: 'column', alignItems: 'center'}}>
                                                                            <Avatar sx={{mb: 2, width: 64, height: 64, color: '#B3B3B3', background: '#F3F3F3'}}>
                                                                                <SvgIcon sx={{width: '32px', height: '32px'}}>
                                                                                    <path
                                                                                        strokeLinecap="round"
                                                                                        strokeLinejoin="round"
                                                                                        strokeWidth="2"
                                                                                        stroke="#8E8E8E"
                                                                                        fill="none"
                                                                                        d="M3 6a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v0a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2m2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8m-9 4h4" />
                                                                                </SvgIcon>
                                                                            </Avatar>
                                                                            <Typography
                                                                                sx={{color: '#8E8E8E', fontSize: 16, fontWeight: 700}}
                                                                                gutterBottom>
                                                                                No options
                                                                            </Typography>
                                                                            <Typography
                                                                                sx={{color: '#8E8E8E', fontSize: 14, fontWeight: 300}}
                                                                                gutterBottom>
                                                                                No available options found.
                                                                            </Typography>
                                                                        </Box>
                                                                    ));
                                                                }}
                                                            </Menu>
                                                        ): (
                                                            <Badge
                                                                variant="dot"
                                                                overlap="circular"
                                                                anchorOrigin={{vertical: 'top', horizontal: 'left'}}
                                                                sx={{...(Boolean(next?.tint) && {
                                                                    '& .MuiBadge-dot': {
                                                                        top: 11,
                                                                        left: -11,
                                                                        width: 8,
                                                                        height: 8,
                                                                        background: `#${next?.tint}`
                                                                    }
                                                                })}}>
                                                                {(next?.text ?? 'N/A')}
                                                            </Badge>
                                                        )) : (head.type ? (((mark?.head == head) && (mark?.item == item)) ? make({type: head.type, rule: head.rule, case: head.case, data: item[head.item], hint: head.hint}, false, true, (save, data) => {
                                                            if (save) {
                                                                if (head.save) {
                                                                    head.save(item, data);
                                                                }
                                                            }

                                                            setMark(null);
                                                        }) : (Boolean(item[head.item]) ? item[head.item] : (
                                                            <Typography color="text.secondary">{head.hint}</Typography>
                                                        ))) : item[head.item])))}
                                                    </TableCell>
                                                ))}

                                                {(Boolean(dash.length) && (
                                                    <TableCell
                                                        sx={{background: 'inherit'}}
                                                        align="right">
                                                        <Stack
                                                            gap={1}
                                                            direction="row"
                                                            justifyContent="flex-end">
                                                            {dash.map((knob, icon: any) => (
                                                                ((knob.view instanceof Function) ? knob.view(item, ((Boolean(properties.wait) || (knob.lock && knob.lock(item))) ?? false)) : (
                                                                    <Tooltip
                                                                        key={knob.name}
                                                                        title={knob.hint}>
                                                                        {((knob.task instanceof Function) ? (
                                                                            <IconButton
                                                                                disabled={(Boolean(properties.wait) || (knob.lock && knob.lock(item)))}
                                                                                onClick={(event) => {
                                                                                    (knob.task as Function)(item);
                                                                                }}
                                                                                sx={{
                                                                                    margin: 0,
                                                                                    padding: 0
                                                                                }}>
                                                                                {(((properties.wait instanceof Object) && ((properties.wait.dash == knob.name) && (properties.wait.item == item[properties.seek]))) ? (
                                                                                    <CircularProgress size={24} />
                                                                                ) : (
                                                                                    <Badge
                                                                                        color="error"
                                                                                        overlap="circular"
                                                                                        anchorOrigin={{vertical: 'top', horizontal: 'right'}}
                                                                                        badgeContent={((knob.size instanceof Function) ? knob.size(item) : knob.size)}>
                                                                                        <Avatar
                                                                                            sx={(theme) => ({
                                                                                                width: 38,
                                                                                                height: 38,
                                                                                                background: alpha(theme.palette.secondary.light, 0.16)
                                                                                            })}
                                                                                            variant="circular">
                                                                                            <SvgIcon sx={(theme) => (
                                                                                                {
                                                                                                    width: 22,
                                                                                                    height: 22,
                                                                                                    stroke: (knob.fill ? 'none' : (((knob.tint instanceof Function) ? knob.tint(item) : knob.tint) ?? theme.palette.secondary.main)),
                                                                                                    fill: (knob.fill ? (((knob.tint instanceof Function) ? knob.tint(item) : knob.tint) ?? 'currentColor') : 'none')
                                                                                                }
                                                                                            )}>
                                                                                                <g
                                                                                                    strokeLinejoin="round"
                                                                                                    strokeLinecap="round"
                                                                                                    strokeWidth="2">
                                                                                                    {((icon = (knob.icon instanceof Function) ? knob.icon(item) : knob.icon) instanceof Array ? icon.map((path) => (<path d={path} />)) : <path d={icon} />)}
                                                                                                </g>
                                                                                            </SvgIcon>
                                                                                        </Avatar>
                                                                                    </Badge>
                                                                                ))}
                                                                            </IconButton>
                                                                        ) : (
                                                                            <Badge
                                                                                color="error"
                                                                                overlap="circular"
                                                                                anchorOrigin={{vertical: 'top', horizontal: 'right'}}
                                                                                badgeContent={((knob.size instanceof Function) ? knob.size(item) : knob.size)}>
                                                                                <Avatar
                                                                                    sx={(theme) => ({
                                                                                        width: 38,
                                                                                        height: 38,
                                                                                        background: alpha(theme.palette.secondary.light, 0.16)
                                                                                    })}
                                                                                    variant="circular">
                                                                                    <SvgIcon sx={(theme) => (
                                                                                        {
                                                                                            width: 22,
                                                                                            height: 22,
                                                                                            stroke: (knob.fill ? 'none' : (((knob.tint instanceof Function) ? knob.tint(item) : knob.tint) ?? theme.palette.secondary.main)),
                                                                                            fill: (knob.fill ? (((knob.tint instanceof Function) ? knob.tint(item) : knob.tint) ?? 'currentColor') : 'none')
                                                                                        }
                                                                                    )}>
                                                                                        <g
                                                                                            strokeLinejoin="round"
                                                                                            strokeLinecap="round"
                                                                                            strokeWidth="2">
                                                                                            {((icon = (knob.icon instanceof Function) ? knob.icon(item) : knob.icon) instanceof Array ? icon.map((path) => (<path d={path} />)) : <path d={icon} />)}
                                                                                        </g>
                                                                                    </SvgIcon>
                                                                                </Avatar>
                                                                            </Badge>
                                                                        ))}
                                                                    </Tooltip>
                                                                ))
                                                            ))}
                                                        </Stack>
                                                    </TableCell>
                                                ))}
                                            </TableRow>
                                        ))}
                                    </Fragment>
                                )) : (
                                    <TableRow>
                                        <TableCell colSpan={(properties.data.length + (properties.bulk ? 1 : 0) + (dash.length ? 1 : 0))}>
                                            {(Boolean(properties.wait) ? (
                                                <Box sx={{mt: 4, mb: 2, display: 'flex', flexDirection: 'column', alignItems: 'center'}}>
                                                    <CircularProgress size={48} />
                                                </Box>
                                            ) : (
                                                <Box sx={{mt: 4, mb: 2, display: 'flex', flexDirection: 'column', alignItems: 'center'}}>
                                                    <Avatar sx={{mb: 2, width: 96, height: 96, color: '#B3B3B3', background: '#F3F3F3'}}>
                                                        <SvgIcon sx={{width: 64, height: 64}}>
                                                            <g
                                                                strokeLinejoin="round"
                                                                strokeLinecap="round"
                                                                strokeWidth="2"
                                                                stroke="currentColor"
                                                                fill="none">
                                                                <path d="M4 6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2z" />
                                                                <path d="M4 13h3l3 3h4l3-3h3" />
                                                            </g>
                                                        </SvgIcon>
                                                    </Avatar>
                                                    <Typography
                                                        color="text.primary"
                                                        variant="h4"
                                                        gutterBottom>
                                                        {(properties.none?.text ?? 'No objects')}
                                                    </Typography>
                                                    <Typography
                                                        color="text.secondary"
                                                        gutterBottom>
                                                        {(properties.none?.note ?? 'No available objects found.')}
                                                    </Typography>
                                                </Box>
                                            ))}
                                        </TableCell>
                                    </TableRow>
                                ))}
                            </TableBody>
                        </Table>
                    </TableContainer>
                ))}

                {(Boolean(properties.list?.length) && Boolean(properties.load) && (
                    <Grid item>
                        <Stack>
                            <Divider />
                            {(properties.more ? (
                                <Stack sx={{padding: 2, alignItems: 'center', justifyContent: 'space-between'}} direction="row" ref={view}>
                                    <Typography color='text.disabled'>
                                        {`Mostrando ${properties.list?.length} of ${(properties.size ?? properties.list?.length)} objetos`}
                                    </Typography>
                                    {(form.busy && (
                                        <CircularProgress size={24} />
                                    ))}
                                </Stack>
                            ) : (
                                <Stack sx={{padding: 2, alignItems: 'center', justifyContent: 'space-between'}} direction="row" ref={view}>
                                    <Typography color='text.disabled'>
                                        {`Mostrando desde ${((properties.page * (properties.take?.pick ?? 16)) + 1)} hasta ${Math.min(((properties.page + 1) * (properties.take?.pick ?? 16)), (properties.size ?? properties.list?.length))} de ${(properties.size ?? properties.list?.length)} registros.`}
                                    </Typography>
                                    <Pagination
                                        disabled={Boolean(properties.wait)}
                                        variant="outlined"
                                        shape="rounded"
                                        color="primary"
                                        count={Math.ceil(((properties.size ?? properties.list?.length) / (properties.take?.pick ?? 16)))}
                                        page={(properties.page + 1)}
                                        onChange={(event, page) => {
                                            setForm((last) => ({
                                                ...last,
                                                page: page - 1
                                            }));
                                        }}
                                        showFirstButton
                                        showLastButton />
                                </Stack>
                            ))}
                        </Stack>
                    </Grid>
                ))}
            </Grid>
        </Card>
    );
}