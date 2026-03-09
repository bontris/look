import React, {ReactElement, ReactNode, Fragment, useRef, useState, useEffect} from "react";

import {useForm, Controller, SubmitHandler, Control, FormState, FieldErrors, useFieldArray, UseFormRegister, Field} from "react-hook-form";

import {Editor, RichUtils, EditorState, ContentState, convertToRaw, convertFromHTML} from "draft-js";

import {convertToHTML} from "draft-convert";

import { SketchPicker } from "react-color";

import "dayjs/locale/es";

import {Link} from "react-router-dom";

import {
    Box,
    Chip,
    Fade,
    Grid,
    Card,
    Step,
    Paper,
    Stack,
    Table,
    Input,
    Dialog,
    Button,
    Select,
    Avatar,
    Switch,
    Popper,
    Stepper,
    Toolbar,
    Tooltip,
    Divider,
    SvgIcon,
    MenuItem,
    Collapse,
    Checkbox,
    TableRow,
    StepLabel,
    TableBody,
    TableCell,
    TableHead,
    TextField,
    IconButton,
    CardHeader,
    Typography,
    InputLabel,
    FormControl,
    DialogTitle,
    CardContent,
    StepContent,
    Autocomplete,
    DialogContent,
    DialogActions,
    FormHelperText,
    TableContainer,
    InputAdornment,
    TablePagination,
    FormControlLabel,
    CircularProgress,
    DialogContentText,
    ClickAwayListener,
    InputBase
} from "@mui/material";

import dayjs from "dayjs";

import {DatePicker} from "@mui/x-date-pickers/DatePicker";

import {AdapterDayjs} from "@mui/x-date-pickers/AdapterDayjs";

import {LocalizationProvider} from "@mui/x-date-pickers";

import type {Null} from "./../types/Null";

import type {Hash} from "./../types/Hash";

type Data = {
    [name: string]: any;
};

type Pick = {
    [name: string]: {
        name: string | undefined;
        done: boolean;
    };
};

const Item = ({next, from, item, field, setValue, getValues}: {next?: any, from?: any, item: any, field: any, setValue: Function, getValues: Function}) => {
    const [open, setOpen] = useState(false);

    const [show, setShow] = useState(false);

    const node = useRef<HTMLInputElement>(null);

    switch (item.type) {
        case 'turn':
            return (
                <Switch
                    defaultChecked={Boolean(field.value)}
                    disabled={field.disabled}
                    onChange={((event) => {
                        if (item.live) {
                            item.live(event.target.checked);
                        }
                    })} />
            );
        case 'pass':
            return (
                <TextField
                    type={(show ? 'text' : 'password')}
                    value={(field.value ?? '')}
                    disabled={field.disabled}
                    placeholder={item.hint}
                    helperText={item.help}
                    InputProps={{
                        endAdornment: (
                            <InputAdornment position="end">
                                <IconButton
                                    onClick={(event) => {
                                        setShow((last) => (last ? false : true));
                                    }}
                                    edge="end"
                                    sx={{margin: 0, padding: '6px'}}>
                                    <SvgIcon sx={{width: '16px', height: '16px'}}>
                                        {(show ? (
                                            <g
                                                strokeLinejoin="round"
                                                strokeLinecap="round"
                                                strokeWidth="2"
                                                stroke="currentColor"
                                                fill="none">
                                                <path d="M10.585 10.587a2 2 0 0 0 2.829 2.828" />
                                                <path d="M16.681 16.673A8.7 8.7 0 0 1 12 18q-5.4 0-9-6q1.908-3.18 4.32-4.674m2.86-1.146A9 9 0 0 1 12 6q5.4 0 9 6q-1 1.665-2.138 2.87M3 3l18 18" />
                                            </g>
                                        ) : (
                                            <g
                                                strokeLinejoin="round"
                                                strokeLinecap="round"
                                                strokeWidth="2"
                                                stroke="currentColor"
                                                fill="none">
                                                <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0-4 0"/>
                                                <path d="M21 12q-3.6 6-9 6t-9-6q3.6-6 9-6t9 6"/>
                                            </g>
                                        ))}
                                    </SvgIcon>
                                </IconButton>
                            </InputAdornment>
                        )
                    }}
                    onChange={((event) => {
                        if (item.live) {
                            item.live(event.target.value);
                        }
                    })}
                    fullWidth />
            );
        case 'area':
            return (
                <TextField
                    rows={(item.rows ?? 4)}
                    value={(field.value ?? '')}
                    disabled={field.disabled}
                    placeholder={(item.hint ?? '')}
                    helperText={item.help}
                    InputProps={{
                        readOnly: item.lock
                    }}
                    multiline
                    fullWidth />
            );
        case 'tint':
            return (
                <React.Fragment>
                    <TextField
                        inputRef={node}
                        value={(field.value ?? '')}
                        disabled={field.disabled}
                        placeholder={item.hint}
                        helperText={item.help}
                        onChange={((event) => {
                            if (item.live) {
                                item.live(event.target.value);
                            }
                        })}
                        onFocus={((event) => {
                            setOpen(true)
                        })}
                        onClick={((event) => {
                            setOpen(true)
                        })}
                        fullWidth/>
                        <Popper
                            sx={{zIndex: (theme) => (theme.zIndex.drawer + 1), borderRadius: '6px'}}
                            open={open}
                            anchorEl={node.current}
                            placement="bottom"
                            transition>
                            {({TransitionProps, placement}) => (
                                <Fade
                                    {...TransitionProps}
                                    style={{
                                        transformOrigin: (placement == 'bottom-end') ? 'right top' : 'left top'
                                    }}>
                                    <Paper className="shadow">
                                        <ClickAwayListener onClickAway={event => {
                                            if (((event.target == node.current) == false)) {
                                                setOpen(false);
                                            }
                                        }}>
                                            <SketchPicker
                                                color={'#556BD6'} onChange={((color) => {
                                                field.onChange(color.hex);

                                                //setOpen(false);
                                            })} />
                                        </ClickAwayListener>
                                    </Paper>
                                </Fade>
                            )}
                        
                    </Popper>
                </React.Fragment>
            );
        case 'rich':
            const [editorState, setEditorState] = useState(EditorState.createWithContent(ContentState.createFromBlockArray(convertFromHTML((field.value ?? '')).contentBlocks)));

            useEffect(() => {
                field.onChange(convertToHTML(editorState.getCurrentContent()));
            }, [editorState]);

            return (
                <Paper variant="outlined">
                    <Toolbar>
                        <Stack
                            direction="row"
                            spacing={1}>
                            {[
                                {
                                    name: 'Bold',
                                    type: 'BOLD',
                                    icon: 'M7 5h6a3.5 3.5 0 0 1 0 7H7zm6 7h1a3.5 3.5 0 0 1 0 7H7v-7',
                                    mode: 'inline'
                                },
                                {
                                    name: 'Italic',
                                    type: 'ITALIC',
                                    icon: 'M11 5h6M7 19h6m1-14l-4 14',
                                    mode: 'inline'
                                },
                                {
                                    name: 'Underline',
                                    type: 'UNDERLINE',
                                    icon: 'M7 5v5a5 5 0 0 0 10 0V5M5 19h14',
                                    mode: 'inline'
                                },
                                {
                                    name: 'Unordered List',
                                    type: 'unordered-list-item',
                                    icon: 'M11 6h9m-9 6h9m-9 6h9M4 10V5.5a1.5 1.5 0 0 1 3 0V10M4 8h3M4 20h1.5a1.5 1.5 0 0 0 0-3H4h1.5a1.5 1.5 0 0 0 0-3H4z',
                                    mode: 'block'
                                },
                                {
                                    name: 'Ordered List',
                                    type: 'ordered-list-item',
                                    icon: 'M11 6h9m-9 6h9m-8 6h8M4 16a2 2 0 1 1 4 0c0 .591-.5 1-1 1.5L4 20h4M6 10V4L4 6',
                                    mode: 'block'
                                },
                                {
                                    name: 'Header One',
                                    type: 'header-one',
                                    icon: 'M19 18v-8l-2 2M4 6v12m8-12v12m-1 0h2M3 18h2m-1-6h8M3 6h2m6 0h2',
                                    mode: 'block'
                                },
                                {
                                    name: 'Header Two',
                                    type: 'header-two',
                                    icon: 'M17 12a2 2 0 1 1 4 0c0 .591-.417 1.318-.816 1.858L17 18.001h4M4 6v12m8-12v12m-1 0h2M3 18h2m-1-6h8M3 6h2m6 0h2',
                                    mode: 'block'
                                },
                                {
                                    name: 'Header Three',
                                    type: 'header-three',
                                    icon: 'M19 14a2 2 0 1 0-2-2m0 4a2 2 0 1 0 2-2M4 6v12m8-12v12m-1 0h2M3 18h2m-1-6h8M3 6h2m6 0h2',
                                    mode: 'block'
                                }
                            ].map((item, next) => (
                                <IconButton
                                    key={next}
                                    color={(((item.mode == 'block') ? (editorState.getCurrentContent()
                                                                                  .getBlockForKey(editorState.getSelection().getStartKey())
                                                                                  .getType() == item.type) : editorState.getCurrentInlineStyle().has(item.type)) ? 'primary' : 'secondary')}
                                    title={item.name}
                                    onClick={(event) => {
                                        if ((item.mode == 'block')) {
                                            setEditorState(RichUtils.toggleBlockType(editorState, item.type))
                                        } else {
                                            setEditorState(RichUtils.toggleInlineStyle(editorState, item.type))
                                        }
                                    }}>
                                    <SvgIcon sx={{width: '24px', height: '24px'}}>
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            strokeWidth="2"
                                            stroke="currentColor"
                                            fill="none"
                                            d={item.icon} />
                                    </SvgIcon>
                                </IconButton>
                            ))}
                        </Stack>
                    </Toolbar>

                    <Divider />

                    <Paper
                        sx={{px: 2, py: 4}}
                        elevation={0}>
                        <Editor
                            editorState={editorState}
                            /*handleKeyCommand={(command) => {
                                const state = RichUtils.handleKeyCommand(editorState, command);

                                if (state) {
                                    setEditorState(state);

                                    return true;
                                }

                                return false;
                            }}*/
                            onChange={(state) => {
                                setEditorState(state);
                            }}
                            onFocus={(event) => {
                                console.log('focus', event)
                            }}
                            onBlur={(event) => {
                                console.log('blur', event)
                            }}
                            placeholder={(item.hint ?? '')} />  
                    </Paper>
                </Paper>
            );
        case 'file':
            const [path, setPath] = useState<string>((item.path instanceof Function ? item.path(field.value) : field.value));

            const file = document.createElement('input');

            file.type = 'file';

            file.accept = (item.kind ?? (item.snap ? [
                'image/jpeg',
                'image/png'
            ] : [
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/vnd.google-apps.spreadsheet',
                'application/vnd.ms-powerpoint',
                'application/vnd.ms-excel',
                'application/msword',
                'application/pdf',
                'text/csv'
             ])).join(', ');

            file.addEventListener('change', () => {
                let pick = file.files?.item(0);

                if (pick) {
                    if (item.snap) {
                        let handle = new FileReader();

                        handle.readAsDataURL(pick);

                        handle.onload = (event) => {
                            setPath(event?.target?.result as string);
                        }
                    }

                    if (item.live) {
                        item.live(pick);
                    }

                    field.onChange(pick);
                }
            }, false);

            return ((item.snap ? (
                <Stack
                alignItems="center"
                    direction="row"
                    gap={2}>
                    <Avatar
                        variant="rounded"
                        src={(path ?? '')}
                        sx={(theme) => ({width: 100, height: 100, background: theme.palette.primary.main})} />
                    <Stack gap={1}>
                        <Stack
                            direction="row"
                            gap={2}>
                            <Button
                                disabled={field.disabled}
                                variant="contained"
                                color="primary"
                                onClick={(event) => {
                                    file.click();
                                }}>
                                {item.text}
                            </Button>
                            <Button
                                disabled={((Boolean(path) == false) || field.disabled)}
                                variant="contained"
                                color="secondary"
                                onClick={(event) => {
                                    field.onChange(null);

                                    if (item.live) {
                                        item.live(null);
                                    }

                                    if (item.snap) {
                                        setPath('');
                                    }
                                }}>
                                Eliminar
                            </Button>
                        </Stack>
                        <Typography color="text.secondary">
                            {(item.hint ?? 'Selecciona una imágen JPEG ó PNG')}
                        </Typography>
                    </Stack>
                </Stack>
            ) : ((item.drop ? (
                'drop'
            ) : (
                <TextField
                    value={(field.value?.name ?? '')}
                    disabled={field.disabled}
                    placeholder={item.hint}
                    helperText={item.help}
                    InputProps={{
                        startAdornment: (
                            <SvgIcon sx={{width: '24px', height: '24px', margin: '0px 8px 0px 0px'}}>
                                <g
                                    strokeLinejoin="round"
                                    strokeLinecap="round"
                                    strokeWidth="2"
                                    stroke="currentColor"
                                    fill="none">
                                    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                    <path d="M17 21H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7l5 5v11a2 2 0 0 1-2 2m-5-10v6" />
                                    <path d="M9.5 13.5L12 11l2.5 2.5" />
                                </g>
                            </SvgIcon>
                        ),
                        endAdornment: (
                            <IconButton
                                sx={{margin: '0px 0px 0px 8px', padding: '4px', visibility: ((field.value instanceof File) ? 'visible' : 'hidden')}}
                                onClick={(event) => {
                                    event.stopPropagation();

                                    field.onChange(null);
                                }}>
                                <SvgIcon sx={{width: '18px', height: '18px'}}>
                                    <path
                                        strokeLinejoin="round"
                                        strokeLinecap="round"
                                        strokeWidth="2"
                                        d="M12 2c5.523 0 10 4.477 10 10s-4.477 10-10 10S2 17.523 2 12S6.477 2 12 2m3.6 5.2a1 1 0 0 0-1.4.2L12 10.333L9.8 7.4a1 1 0 1 0-1.6 1.2l2.55 3.4l-2.55 3.4a1 1 0 1 0 1.6 1.2l2.2-2.933l2.2 2.933a1 1 0 0 0 1.6-1.2L13.25 12l2.55-3.4a1 1 0 0 0-.2-1.4" />
                                </SvgIcon>
                            </IconButton>
                        ),
                        readOnly: true
                    }}
                    onClick={(event) => {
                        file.click();
                    }}
                    fullWidth />
            )))));
        case 'date':
            return (
                <LocalizationProvider dateAdapter={AdapterDayjs} adapterLocale="es">
                    <DatePicker
                        value={(field.value ? dayjs(field.value) : null)}
                        disabled={Boolean(field.disabled)}
                        slotProps={{
                            textField: {
                                fullWidth: true,
                                placeholder: item.hint
                            }
                        }}
                        onChange={(date) => {
                            field.onChange(dayjs(date).format('YYYY-MM-DD'));
                        }} />
                </LocalizationProvider>
            );
        case 'menu':
            return (
                <Select
                    value={(item.pick ? (field.value ?? []) : (field.value ?? null))}
                    disabled={field.disabled}
                    multiple={item.pick}
                    renderValue={(data: any) => (
                        (field.value instanceof Array) ? (field.value?.map((next: any) => (item.list?.find((menu: any) => (menu.item == next))?.text ?? next)).join(', ') || item.text) :
                                                         (item.list?.find((menu: any) => (menu.item == field.value))?.text ?? item.hint)
                    )}
                    onChange={(event) => {
                        field.onChange(event.target.value);

                        if (item.live) {
                            setTimeout(() => {
                                item.live(event.target.value, getValues());
                            }, 100);
                        }
                    }}>
                    {item.list.map((menu: any) => (
                        <MenuItem
                            key={menu.item}
                            value={menu.item}>
                                {(item.pick && (
                                    <Checkbox
                                        sx={{margin: '0px', padding: '0px'}}
                                        checked={Boolean(field.value?.find((next: any) => (menu.item == next)))} />
                                ))}
                            {menu.text}
                        </MenuItem>
                    ))}
                </Select>
            )
        case 'list':
            return (
                <Autocomplete
                    value={(item.span ? (item.list.filter((next: any) => (field.value?.some((data: any) => (next.item == data))))) : (item.list?.find((next: any) => (next.item == field.value)) ?? null))}
                    options={item.list}
                    disabled={field.disabled}
                    getOptionLabel={(item: any) => item.text}
                    multiple={item.span}
                    autoHighlight
                    renderOption={(data, item) => {
                        const {...rest} = data;
                        return (
                            <Stack
                                {...rest}
                                sx={{alignItems: 'center'}}
                                spacing={1}
                                component="li"
                                direction="row">
                                {(Boolean((item.icon ?? item.none)) && (
                                    <Avatar
                                        src={item.icon}
                                        sx={{
                                            width: 34,
                                            height: 34
                                        }}>
                                        {(item.none ?? item.text[0])}
                                    </Avatar>
                                ))}
                                <Stack
                                    sx={{overflow: 'hidden'}}
                                    direction="column">
                                    <Typography
                                        color={(theme) => (theme.palette.text.primary)}
                                        noWrap>
                                        {item.text}
                                    </Typography>
                                    {(Boolean(item.hint) && (
                                        <Typography
                                            color={(theme) => (theme.palette.text.secondary)}
                                            noWrap>
                                            {item.hint}
                                        </Typography>
                                    ))}
                                </Stack>
                            </Stack>
                        );
                    }}
                    renderTags={(list, find) => (
                        list.map((chip, next) => {
                            const {key, ...rest} = find({index: next});

                            const data = item.list?.find((some: any) => (some[(item.data ?? 'item')] == (chip[(item.data ?? 'item')] ?? chip))) ?? chip;

                            return (
                                <Chip
                                    sx={{px: 0, py: 2}}
                                    key={key}
                                    label={((item.chip instanceof Function ? item.chip(data) : data[(item.chip ?? 'text')]) ?? data)}
                                    {...rest} />
                            );
                        })
                    )}
                    renderInput={(rest) => (
                        <TextField
                            {...rest}
                            placeholder={item.hint} />
                    )}
                    onChange={(event, value) => {console.log('data', field.name, ((item.span ? (value?.map((data: any) => (data.item)) ?? []) : value?.item) ?? null));
                        field.onChange(((item.span ? (value?.map((data: any) => (data.item)) ?? []) : value?.item) ?? null));

                        if (item.live) {
                            setTimeout(() => {
                                item.live(value, getValues(), next);
                            }, 100);
                        }
                    }}
                    fullWidth
                    blurOnSelect />
            );
        case 'text':
            const [pick, setPick] = useState<Null<any>>(null);

            const [find, setFind] = useState<Null<string>>(null);

            useEffect(() => {
                const data = getValues((from ? `${from.name}.${next}.${item.menu?.item}` : item.menu?.item));

                setPick(((item.menu?.list && item.menu.list.find((next: any) => (next[(item.data ?? 'code')] === data))) ?? data?? null));
            }, [item.menu?.list, getValues((from ? `${from.name}.${next}.${item.menu?.item}` : item.menu?.item))]);

            useEffect(() => {
                if (item.menu) {
                    if (item.menu.load) {
                        let time = setTimeout(() => {
                            item.menu.load(find);
                        }, 600);
    
                        return (() => {clearTimeout(time)});
                    }
                }
            }, [find]);

            return (
                <TextField
                    value={(field.value ?? '')}
                    disabled={field.disabled}
                    placeholder={item.hint}
                    helperText={item.help}
                    InputProps={{
                        startAdornment: (Boolean(item.menu) && (
                            <Autocomplete
                                value={pick}
                                sx={{width: 200}}
                                options={(item.menu.list ?? [])}
                                disabled={field.disabled}
                                readOnly={(field.readOnly || item.lock)}
                                getOptionLabel={(data: any) => (data['name'] ?? data)}
                                noOptionsText={(item.wait ? (Boolean(find) ? 'Buscando...' : 'Cargando...') : 'No hay opciones disponibles.')}
                                filterOptions={(item.menu.load ? (list) => (item.wait ? [] : list) : (void null))}
                                isOptionEqualToValue={(data: any, pick: any) => ((data[(item.data ?? 'code')] == (pick[(item.data ?? 'code')] ?? pick)))}
                                renderOption={(data, next) => {
                                    const {...rest} = data;

                                    return (
                                        <Stack
                                            {...rest}
                                            sx={{alignItems: 'center'}}
                                            spacing={1}
                                            component="li"
                                            direction="row">
                                            {(Boolean(item.face) && (
                                                <Avatar
                                                    src={(item.face instanceof Function ? item.face(next) : next[item.face])}
                                                    sx={{
                                                        width: 40,
                                                        height: 40
                                                    }}>
                                                    {next['name']?.match(/\b(\w)/g)?.slice(0, 2)?.join('')}
                                                </Avatar>
                                            ))}
                                            <Stack
                                                sx={{overflow: 'hidden'}}
                                                direction="column">
                                                <Typography
                                                    color="text.primary"
                                                    noWrap>
                                                    {next['name']}
                                                </Typography>
                                                {(Boolean(item.note) && (
                                                    <Typography
                                                        color="text.secondary"
                                                        noWrap>
                                                        {(item.note instanceof Function ? item.note(next) : next[item.note])}
                                                    </Typography>
                                                ))}
                                            </Stack>
                                        </Stack>
                                    );
                                }}
                                renderTags={(list, find) => (
                                    list.map((chip, next) => {
                                        const {key, ...rest} = find({index: next});

                                        const data = item.menu.list.find((some: any) => (some[(item.data ?? 'code')] == (chip[(item.data ?? 'code')] ?? chip))) ?? chip;

                                        return (
                                            <Chip
                                                sx={{px: 0, py: 2}}
                                                key={key}
                                                label={((item.text instanceof Function ? item.text(data, false) : data['name']) ?? data)}
                                                {...rest} />
                                        );
                                    })
                                )}
                                renderInput={(rest) => (
                                    <InputBase
                                        {...rest}
                                        {...rest.InputProps}
                                        readOnly={true}
                                        onChange={(event) => {
                                            setFind(event.target.value?.trim());
                                        }} />
                                )}
                                onOpen={(event) => {
                                    if ((item.menu.list == null)) {
                                        if (item.menu.load) {
                                            item.menu.load(null);
                                        }
                                    }
                                }}
                                onChange={(event, data, mode) => {
                                    setValue((from ? `${from.name}.${next}.${item.menu?.item}` : item.menu?.item), (data = ((mode == 'clear') ? null : (data?.[item.data] ?? data))));

                                    if (item.menu.live) {
                                        setTimeout(() => {
                                            item.menu.live(data, getValues());
                                        }, 100);
                                    }
                                }}
                                blurOnSelect
                                disablePortal
                                autoHighlight
                                disableListWrap
                                disableClearable />
                        )),
                        readOnly: item.lock
                    }}
                    onChange={((event) => {
                        if (item.live) {
                            item.live(event.target.value);
                        }
                    })}
                    fullWidth />
            );
        default:
            return (
                <Fragment>
                    {(false && (
                        <Typography
                            variant="h6"
                            gutterBottom>
                            Name
                        </Typography>
                    ))}
                    <Typography gutterBottom>
                        {(item.cast instanceof Function ? item.cast(field.value) : field.value)}
                    </Typography>
                </Fragment>
            )

    }
}

const Icon = (properties: any) => {
    const {icon, active, completed} = properties;
   
    return (
        <Avatar
            sx={{width: 38, height: 38, color: (active ? '#FFFFFF' : (completed ? '#ABA4F6' : '#6F6D7D')), background: (active ? '#7367F0' : (completed ? '#F4F3FE' : '#F1F0F2'))}}
            variant="rounded">
            {icon}
        </Avatar>
    );
}

const List = ({form: {
    getValues,
    setValue,
    control,
    errors
}, item, data}: {item: any, data: any, form: {errors: FieldErrors<{
    [name: string]: any
}>, control: Control, setValue: Function, getValues: Function}}) => {
    const {fields, remove, append, move} = useFieldArray({
        control,
        name: item.name
    });

    return ((item.grid ? (
        <Card variant="outlined">
            <Table>
                <TableHead>
                    <TableRow>
                        {item.form.list.map((item: any) => (
                            <TableCell
                                key={item.name}>
                                {(item.text ?? item.name)}
                            </TableCell>
                        ))}
                    </TableRow>
                </TableHead>
                <TableBody>
                    {(Boolean(fields.length) ? fields.map((data: any, next: number) => (
                        ((((item.foot ?? null) == null) || (data == (data = item.foot(data) ?? data))) ? (
                            <TableRow key={data.id}>
                                {item.form?.list.map((tile: any, slot: number) => (
                                    <TableCell width={(tile.size && ({full: '100%', wide: '70%', half: '50%', tiny: '30%'} as const)[tile.size as 'tiny' | 'half' | 'wide' | 'full'])} key={tile.name}>
                                        <Controller
                                            name={`${item.name}.${next}.${tile.name}`}
                                            control={control}
                                            rules={{required: tile.bind, pattern: tile.rule && {
                                                value: tile.rule,
                                                message: tile.fail ?? 'The value is not valid.'
                                            }}}
                                            //disabled={(lock || pick[pane.name].done)}
                                            render={({field}) => (
                                                <FormControl
                                                    {...field}
                                                    error={Boolean((errors[item.name] as any)?.[next]?.[tile.name ?? slot])}
                                                    fullWidth>
                                                    <Item
                                                        next={next}
                                                        item={tile}
                                                        from={item}
                                                        field={field}
                                                        setValue={setValue}
                                                        getValues={getValues} />
                                                    {(Boolean((errors[item.name] as any)?.[next]?.[tile.name ?? slot]) && (
                                                        <FormHelperText>
                                                            {`${(errors[item.name] as any)[next][tile.name].message}`}
                                                        </FormHelperText>
                                                    ))}
                                                </FormControl>
                                            )} />
                                    </TableCell>
                                ))}
                            </TableRow>
                        ) : (
                            <TableRow>
                                <TableCell colSpan={fields.length}>
                                    {data}
                                </TableCell>
                            </TableRow>
                        ))
                    )) : (
                        <TableRow>
                            <TableCell colSpan={item.form.list.length}>
                                <Box sx={{mt: 4, mb: 2, display: 'flex', flexDirection: 'column', alignItems: 'center'}}>
                                    <Avatar sx={{mb: 2, width: 96, height: 96, color: '#B3B3B3', background: '#F3F3F3'}}>
                                        <SvgIcon sx={{width: '48px', height: '48px'}}>
                                            <path
                                                strokeLinecap="round"
                                                strokeLinejoin="round"
                                                strokeWidth="2"
                                                stroke="#8E8E8E"
                                                fill="none"
                                                d="M3 6a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v0a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2m2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8m-9 4h4"/>
                                        </SvgIcon>
                                    </Avatar>
                                    <Typography
                                        sx={{color: '#8E8E8E', fontSize: 18, fontWeight: 700}}
                                        gutterBottom>
                                        No fields
                                    </Typography>
                                    <Typography
                                        sx={{color: '#8E8E8E', fontSize: 14, fontWeight: 300}}
                                        gutterBottom>
                                        No available fields found.
                                    </Typography>
                                </Box>
                            </TableCell>
                        </TableRow>
                    ))}
                </TableBody>
            </Table>
        </Card>
    ) : (
        <Grid
            spacing={2}
            container>
            {fields.map((data: any, next: number) => (
                <Grid
                    xs={12}
                    key={data.id}
                    item>
                    <Card
                        variant={(item.flat ? (void null) : 'outlined')}
                        sx={(item.flat ? {pb: 0.5, overflow: 'initial', boxShadow: 0} : {padding: 3, overflow: 'initial'})}>
                        <Grid
                            spacing={2}
                            container>
                            {(Boolean(item.form?.push) && (
                                <Grid
                                    xs={12}
                                    justifyContent="flex-end"
                                    container
                                    item>
                                    <IconButton
                                        color="error"
                                        title="Delete"
                                        disabled={(fields.length == item.form?.size)}
                                        onClick={() => {
                                            remove(next);
                                        }}>
                                        <SvgIcon sx={{width: '24px', height: '24px'}}>
                                            <g
                                                strokeLinecap="round"
                                                strokeLinejoin="round"
                                                strokeWidth="2"
                                                stroke="currentColor"
                                                fill="none">
                                                <path d="M4 7h16m-10 4v6m4-6v6M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2l1-12M9 7V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v3" />
                                            </g>
                                        </SvgIcon>
                                    </IconButton>
                                    {(item.form.sort && (
                                        <IconButton
                                            color="primary"
                                            title="Move down"
                                            //disabled={(lock || pick[pane.name].done || (fields.length == next + 1))}
                                            onClick={() => {
                                                move(next, next + 1);
                                            }}>
                                            <SvgIcon sx={{width: '24px', height: '24px'}}>
                                                <g
                                                    strokeLinecap="round"
                                                    strokeLinejoin="round"
                                                    strokeWidth="2"
                                                    stroke="currentColor"
                                                    fill="none">
                                                    <path d="M12 5v14m4-4l-4 4m-4-4l4 4" />
                                                </g>
                                            </SvgIcon>
                                        </IconButton>
                                    ))}
                                    {(item.form.sort && (
                                        <IconButton
                                            color="primary"
                                            title="Move up"
                                            //disabled={(lock || pick[pane.name].done || (next == 0))}
                                            onClick={() => {
                                                move(next, next - 1);
                                            }}>
                                            <SvgIcon sx={{width: '24px', height: '24px'}}>
                                                <g
                                                    strokeLinecap="round"
                                                    strokeLinejoin="round"
                                                    strokeWidth="2"
                                                    stroke="currentColor"
                                                    fill="none">
                                                    <path d="M12 5v14m4-10l-4-4M8 9l4-4" />
                                                </g>
                                            </SvgIcon>
                                        </IconButton>
                                    ))}
                                </Grid>
                            ))}
                            <Grid
                                spacing={2}
                                container
                                item>
                                {item.form?.list.map((tile: Hash<any>) => (
                                    (tile.line ? (
                                        <Grid
                                            xs={12}
                                            key={tile.name}
                                            item>
                                            <Typography
                                                color="text.primary"
                                                variant="h5"
                                                gutterBottom>
                                                {tile.text}
                                            </Typography>
                                            {(tile.hint && (
                                                <Typography
                                                    color="text.secondary"
                                                    gutterBottom>
                                                    {tile.hint}
                                                </Typography>
                                            ))}
                                        </Grid>
                                    ) : (
                                        <Grid
                                            xs={({tiny: 3, half: 6, wide: 9, full: 12} as Hash<number>)[(tile.size ?? 'full')]}
                                            key={(tile.name ?? next)}
                                            item>
                                            <Controller
                                                name={(tile.name ? `${item.name}.${next}.${(tile.name)}` : `${item.name}.${next}`)}
                                                control={control}
                                                rules={{required: tile.bind, pattern: tile.rule && {
                                                    value: tile.rule,
                                                    message: tile.fail ?? 'The value is not valid.'
                                                }}}
                                                //disabled={(lock || pick[pane.name].done)}
                                                render={({field}) => (
                                                    <FormControl
                                                        {...field}
                                                        error={(tile.name ? Boolean((errors[item.name] as any)?.[next]?.[(tile.name)]) : Boolean((errors[item.name] as any)?.[next]))}
                                                        fullWidth>
                                                        {((tile.snap ? (
                                                            <Item
                                                                next={next}
                                                                item={tile}
                                                                from={item}
                                                                field={field}
                                                                setValue={setValue}
                                                                getValues={getValues} />
                                                        ) : ((item.type == 'turn') ? (
                                                            <FormControlLabel
                                                                className={`${Boolean(item.bind) && 'bind'}`}
                                                                checked={Boolean(field.value)}
                                                                control={<Switch />}
                                                                label={(item.text ?? item.name)} />
                                                        ) : (
                                                            <Fragment>
                                                                <InputLabel
                                                                    className={`${(Boolean(tile.bind) && 'bind')}`}
                                                                    htmlFor={(tile.name ? `${item.name}.${next}.${(tile.name)}` : `${item.name}.${next}`)}>
                                                                    {(tile.text ?? tile.name)}
                                                                </InputLabel>
                                                                <Item
                                                                    next={next}
                                                                    item={tile}
                                                                    from={item}
                                                                    field={field}
                                                                    setValue={setValue}
                                                                    getValues={getValues} />
                                                            </Fragment>
                                                        ))))}
                                                        
                                                        {((tile.name ? Boolean((errors[item.name] as any)?.[next]?.[(tile.name)]) : Boolean((errors[item.name] as any)?.[next])) && (
                                                            <FormHelperText>
                                                                {(tile.name ? `${(errors[item.name] as any)[next][(tile.name)].message}` : `${(errors[item.name] as any)[next].message}`)}
                                                            </FormHelperText>
                                                        ))}
                                                    </FormControl>
                                                )} />
                                        </Grid>
                                    ))
                                ))}
                            </Grid>
                        </Grid>
                    </Card>
                </Grid>
            ))}
            {(Boolean(item.form?.push) && (
                <Grid
                    xs={12}
                    item>
                    <Button
                        sx={{mx: 0}}
                        size="small"
                        variant="contained"
                        disabled={(fields.length == item.form?.high)}
                        onClick={() => {
                            append(item.form?.push(data[item.name]?.length + 1));
                        }}
                        startIcon={
                            <SvgIcon sx={{width: '16px', height: '16px'}}>
                                <g
                                    strokeLinecap="round"
                                    strokeLinejoin="round"
                                    strokeWidth="2"
                                    stroke="currentColor">
                                    <path d="M12 5v14m-7-7h14" />
                                </g>
                            </SvgIcon>
                        }>
                        {(item.text ?? 'Add new item')}
                    </Button>
                </Grid>
            ))}
        </Grid>
    )));
}

export const Form = ({wait, lock, flat, note, name, fail, view, data, quit, save}: {
    wait?: boolean;
    lock?: boolean;
    flat?: boolean,
    note?: string,
    name?: string,
    view: {
        left?: boolean,
        step?: {
            pick: number,
            list: Array<{
                name: string,
                text: string,
                hint: string,
                icon: ReactElement,
                done?: (item: any) => Promise<void>
            }>
        },
        data: Array<{
            type?: 'text' | 'pass' | 'area' | 'rich' | 'pick' | 'turn' | 'tint' | 'date' | 'file' | 'list',
            size?: ((form: Hash<any>) => 'tiny' | 'half' | 'wide' | 'full') | 'tiny' | 'half' | 'wide' | 'full',
            kind?: Array<string>,
            icon?: ReactNode,
            grid?: boolean,
            flat?: boolean,
            lock?: boolean,
            turn?: boolean,
            span?: boolean,
            line?: boolean,
            tile?: boolean,
            pick?: boolean,
            snap?: boolean,
            drop?: boolean,
            text?: string,
            hint?: ((form: any) => ReactNode) | string,
            help?: string,
            fail?: string,
            rule?: RegExp,
            bind?: string,
            step?: number,
            rows?: number,
            name: string,
            menu?: Array<any>,
            form?: {
                size: number,
                high: number,
                sort?: boolean,
                list: Array<{
                    type?: 'text' | 'pass' | 'area' | 'rich' | 'pick' | 'turn' | 'tint' | 'date' | 'file' | 'list',
                    size?: 'tiny' | 'half' | 'wide' | 'full',
                    icon?: ReactNode,
                    lock?: boolean,
                    turn?: boolean,
                    line?: boolean,
                    pick?: boolean,
                    span?: boolean,
                    text?: (item: any, next?: number) => string,
                    mark?: (item: any, next?: number) => string,
                    cast?: (item: any, next?: number) => string,
                    hint?: string,
                    help?: string,
                    fail?: string,
                    rule?: RegExp,
                    bind?: string,
                    step?: number,
                    rows?: number,
                    name: string
                }>,
                push?: (next: number) => Hash<any>
            },
            data?: any,
            foot?: (item: any) => ReactNode | null,
            wipe?: (form?: Hash<any>) => Promise<void>,
            path?: ((form: Hash<any>) => string) | string,
            hide?: ((form: Hash<any>) => boolean) | boolean,
            chip?: ((item: any) => ReactNode) | string,
            live?: (data: any, form?: Hash<any>, next?: any) => Promise<void>
        }>
    },
    data: {
        [name: string]: any
    },
    fail?: {
        [name: string]: string
    },
    quit?: {
        text?: string,
        task: (step?: number, exit?: boolean) => void
    },
    save?: {
        text?: string,
        task: (data: any, step?: number, last?: boolean) => void
    }
}) => {
    const {watch, reset, setError, setValue, getValues, handleSubmit, control, register, formState: {errors}} = useForm({
        defaultValues: data
    });

    const [time, setTime] = useState<NodeJS.Timeout | number | null>(null);

    const onSubmit: SubmitHandler<Hash<any>> = (data: Hash<any>) => {
        //, (data: Hash<any>) => ({...Object.assign({}, ...Object.keys(data).filter((name: string) => (((view.data.find((next: any) => (next.item == name))?.hide ?? false) == false))).map((name: string) => ({[name]: data[name]})))})
        //reset({Nombre: 'Test #4', Codigo: '8888', Dependencia: {"Id":9,"Ud":"736df671-db42-4cb6-8a61-d881cd63b386","Codigo":"114","Nombre":"Archivo General del Municipio"}}, {keepDirtyValues: false});
        if (save) {
            save.task(data, view.step?.pick, (view.step?.list.length == ((view.step?.pick ?? 0) + 1)));
        }
    }

    useEffect(() => {
        if (fail) {
            Object.keys(fail).forEach((name) => {
                setError(name, {
                    type: 'custom',
                    message: fail[name]
                });
            });
        }
    }, [fail]);

    useEffect(() => {
        reset(data, {keepDirtyValues: false});
    }, [data]);

    /*const [pick, setPick] = useState<Pick>(form.reduce((hash: Pick, pane) => {
        if (pane.step?.list.length) {
            hash[pane.name ?? 'none'] = {
                name: pane.step?.pick ?? 'none',
                done: false
            };
        }

        return hash;
    }, {}));*/

    const form = watch();

    return (
        <Grid
            component="form"
            autoComplete="off"
            onSubmit={handleSubmit(onSubmit)}
            spacing={3}
            container
            noValidate>
            {((Boolean(view.step?.list.length) ? (
                <Grid
                    xs={12}
                    item>
                    <Card
                        variant={(flat ? (void null) : 'outlined')}
                        sx={(flat ? {pb: 0.5, boxShadow: 0} : {padding: 3})}>
                        <CardContent sx={{padding: flat ? 0 : 2}}>
                            <Grid
                                spacing={2}
                                container>
                                <Grid
                                    xs={(view.left ? 3 : 12)}
                                    item>
                                    <Stepper
                                        orientation={(view.left ? 'vertical' : 'horizontal')}
                                        activeStep={view.step?.pick}
                                        connector={null}>
                                        {view.step?.list.map((step) => (
                                            <Step
                                                sx={(view.left ? {mb: 1} : {mr: 5})}
                                                key={step.name}>
                                                <StepLabel
                                                    StepIconComponent={Icon}
                                                    icon={step.icon}>
                                                    <Typography sx={{color: 'primaryText', fontSize: '14px', fontWeight: '400', lineHeight: '16px'}}>
                                                        {step.text}
                                                    </Typography>
                                                    <Typography sx={{color: 'secondaryText', fontSize: '13px', fontWeight: '400', lineHeight: '16px'}}>
                                                        {step.hint}
                                                    </Typography>
                                                </StepLabel>
                                            </Step>
                                        ))}
                                    </Stepper>
                                </Grid>
                                <Grid
                                    xs={(view.left ? 9 : 12)}
                                    item>
                                    <Grid
                                        spacing={2}
                                        container>
                                        {view.data.filter((item) => ((((item.hide instanceof Function) ? item.hide(getValues()) : item.hide ?? false) == false) && (item.step == view.step?.pick))).map((item) => (
                                        (item.tile ? (
                                            <Grid
                                                xs={12}
                                                key={item.name}
                                                item>
                                                <List
                                                    item={item}
                                                    data={data}
                                                    form={{errors, control, setValue, getValues}} />
                                            </Grid>
                                        ) : (item.line ? (
                                            <Grid
                                                xs={12}
                                                key={item.name}
                                                item>
                                                <Typography
                                                    color="text.primary"
                                                    variant="h5"
                                                    gutterBottom>
                                                    {item.text}
                                                </Typography>
                                                {(item.hint && ((item.hint instanceof Function) ? item.hint(data) : (
                                                    <Typography
                                                        color="text.secondary"
                                                        gutterBottom>
                                                        {item.hint}
                                                    </Typography>
                                                )))}
                                            </Grid>
                                        ) : (
                                            <Grid
                                                xs={{tiny: 3, half: 6, wide: 9, full: 12}[((item.size instanceof Function ? item.size(getValues()) : item.size) ?? 'full')]}
                                                key={item.name}
                                                item>
                                                <Controller
                                                    name={item.name}
                                                    control={control}
                                                    rules={{required: item.bind, pattern: item.rule && {
                                                        value: item.rule,
                                                        message: item.fail ?? 'The value is not valid.'
                                                    }}}
                                                    disabled={(lock || item.lock)}
                                                    render={({field}) => (
                                                        <FormControl
                                                            {...field}
                                                            error={Boolean(item.name.split('.').reduce((data: Hash<any>, name: string) => (data?.[name]), errors))}
                                                            fullWidth>
                                                            {((item.type == 'turn') ? (
                                                                <FormControlLabel
                                                                    className={`${Boolean(item.bind) && 'bind'}`}
                                                                    checked={Boolean(field.value)}
                                                                    control={<Switch />}
                                                                    label={(item.text ?? item.name)} />
                                                            ) : (
                                                                <Fragment>
                                                                    <InputLabel
                                                                        className={`${Boolean(item.bind) && 'bind'}`}
                                                                        htmlFor={item.name}>
                                                                        {(item.text ?? item.name)}
                                                                    </InputLabel>
                                                                    <Item
                                                                        item={item}
                                                                        field={field}
                                                                        setValue={setValue}
                                                                        getValues={getValues} />
                                                                </Fragment>
                                                            ))}
                                                            
                                                            {(item.name.split('.').reduce((data: Hash<any>, name: string) => (data?.[name]), errors) && (
                                                                <FormHelperText>
                                                                    {`${item.name.split('.').reduce((data: Hash<any>, name: string) => (data?.[name]), errors).message}`}
                                                                </FormHelperText>
                                                            ))}
                                                        </FormControl>
                                                    )} />
                                            </Grid>
                                        )))))}
                                        {/*<Grid
                                            xs={12}
                                            item>
                                            <Stack
                                                direction="row"
                                                justifyContent="space-between">
                                                <Button
                                                    sx={{mt: 1, mx: 0}}
                                                    color="secondary"
                                                    variant="contained"
                                                    onClick={() => {
                                                        const step = pane.step?.findIndex((item) => ((pick[pane.name].name == item.name)));

                                                        if (step) {
                                                            setPick((last) => (
                                                                {...last, [pane.name]: {...last[pane.name], name: pane.step?.[(step - 1)].name}}
                                                            ));
                                                        }
                                                    }}
                                                    disabled={(lock || (pick[pane.name].name == pane.step?.[0].name))}>
                                                    Back
                                                </Button>
                                                <Button
                                                    sx={{mt: 1, mx: 0}}
                                                    color={((pick[pane.name].name == pane.step?.[(pane.step?.length - 1)]?.name) ? 'success' : 'primary')}
                                                    variant="contained"
                                                    onClick={() => {
                                                        const step = (pane.step?.findIndex((item) => ((pick[pane.name].name == item.name))) ?? 0);

                                                        if ((pane.step?.length == (step + 1))) {
                                                            setPick((last) => (
                                                                {...last, [pane.name]: {...last[pane.name], done: true}}
                                                            ));
                                                        } else {
                                                            setPick((last) => (
                                                                {...last, [pane.name]: {...last[pane.name], name: pane.step?.[(step + 1)].name}}
                                                            ));
                                                        }
                                                    }}
                                                    disabled={(lock || pick[pane.name].done)}>
                                                    {((pick[pane.name].name == pane.step?.[(pane.step?.length - 1)]?.name) ? 'Done' : 'Next')}
                                                </Button>
                                            </Stack>
                                        </Grid>*/}
                                    </Grid>
                                    {(Boolean(note) && (
                                        <Grid
                                            xs={12}
                                            item>
                                            <Typography
                                                sx={{color: 'text.disabled', pt: 2}}
                                                variant="body1"
                                                gutterBottom>
                                                {note}
                                            </Typography>
                                        </Grid>
                                    ))}
                                    <Grid
                                        sx={{pt: 4}}
                                        xs={12}
                                        item>
                                        <Stack
                                            justifyContent={'space-between'}
                                            direction="row"
                                            spacing={2}>
                                            {(Boolean(quit) && (
                                                <Button
                                                    color="secondary"
                                                    variant="contained"
                                                    disabled={lock}
                                                    startIcon={
                                                        <SvgIcon sx={{width: '16px', height: '16px'}}>
                                                            <path
                                                                strokeLinecap="round"
                                                                strokeLinejoin="round"
                                                                strokeWidth="2"
                                                                stroke="currentColor"
                                                                fill="none"
                                                                d={((view.step?.pick ?? 1) ? 'M5 12h14M5 12l4 4m-4-4l4-4' : 'M18 6L6 18M6 6l12 12')} />
                                                        </SvgIcon>
                                                    }
                                                    onClick={(event) => {
                                                        quit?.task(((view.step?.pick ?? 1) ? ((view.step?.pick ?? 1) - 1) : 0), ((view.step?.pick ?? 1) == 0));
                                                    }}>
                                                    {(quit?.text ?? ((view.step?.pick ?? 1) ? 'Back' : 'Quit'))}
                                                </Button>
                                            ))}
                                            {(Boolean(save) && (
                                                <Button
                                                    type="submit"
                                                    color={((view.step?.list.length == ((view.step?.pick ?? 0) + 1)) ? 'success' : 'primary')}
                                                    variant="contained"
                                                    disabled={lock}
                                                    endIcon={
                                                        <SvgIcon sx={{width: '16px', height: '16px'}}>
                                                            <path
                                                                strokeLinecap="round"
                                                                strokeLinejoin="round"
                                                                strokeWidth="2"
                                                                stroke="currentColor"
                                                                fill="none"
                                                                d={((view.step?.list.length == ((view.step?.pick ?? 0) + 1)) ? 'm5 12l5 5L20 7' : 'M5 12h14m-4 4l4-4m-4-4l4 4')} />
                                                        </SvgIcon>
                                                    }>
                                                    {(save?.text ?? ((view.step?.list.length == ((view.step?.pick ?? 0) + 1)) ? 'Save' : 'Next'))}
                                                </Button>
                                            ))}
                                        </Stack>
                                    </Grid>
                                </Grid>
                            </Grid>
                        </CardContent>
                    </Card>
                </Grid>
            ) : (
                <Grid
                    xs={12}
                    item>
                    <Card
                        variant={(flat ? (void null) : 'outlined')}
                        sx={(flat ? {pb: 0.5, boxShadow: 0} : {padding: 3})}>
                        <CardContent sx={{padding: flat ? 0 : 2}}>
                            <Grid
                                spacing={2}
                                container>
                                {view.data.filter((item) => ((((item.hide instanceof Function) ? item.hide(getValues()) : item.hide ?? false) == false))).map((item) => (
                                    (item.tile ? (
                                        <Grid
                                            xs={12}
                                            key={item.name}
                                            item>
                                            <List
                                                item={item}
                                                data={data}
                                                form={{errors, control, setValue, getValues}}/>
                                        </Grid>
                                    ) : (item.line ? (
                                        <Grid
                                            xs={12}
                                            key={item.name}
                                            item>
                                            <Typography
                                                color="text.primary"
                                                variant="h5"
                                                gutterBottom>
                                                {item.text}
                                            </Typography>
                                            {(item.hint && ((item.hint instanceof Function) ? item.hint(data) : (
                                                <Typography
                                                    color="text.secondary"
                                                    gutterBottom>
                                                    {item.hint}
                                                </Typography>
                                            )))}
                                        </Grid>
                                    ) : (
                                        <Grid
                                            xs={{tiny: 3, half: 6, wide: 9, full: 12}[((item.size instanceof Function ? item.size(getValues()) : item.size) ?? 'full')]}
                                            key={item.name}
                                            item>
                                            <Controller
                                                name={item.name}
                                                control={control}
                                                rules={{required: item.bind, pattern: item.rule && {
                                                    value: new RegExp(item.rule),
                                                    message: item.fail ?? 'El valor no es válido.'
                                                }}}
                                                disabled={lock}
                                                render={({field}) => (
                                                    <FormControl
                                                        {...field}
                                                        error={Boolean(item.name.split('.').reduce((data: Hash<any>, name: string) => (data?.[name]), errors))}
                                                        fullWidth>
                                                        {(item.snap ? (
                                                            <Item
                                                                item={item}
                                                                field={field}
                                                                setValue={setValue}
                                                                getValues={getValues} />
                                                        ) : ((item.type == 'turn') ? (
                                                            <FormControlLabel
                                                                className={`${Boolean(item.bind) && 'bind'}`}
                                                                checked={Boolean(field.value)}
                                                                control={<Switch />}
                                                                label={(
                                                                    <Stack>
                                                                        <Typography color="text.primary">
                                                                            {(item.text ?? item.name)}
                                                                        </Typography>
                                                                        {(Boolean(item.hint) && (
                                                                            <Typography color="text.secondary">
                                                                                {(item.hint instanceof Function ? item.hint(getValues()) : item.hint)}
                                                                            </Typography>
                                                                        ))}
                                                                    </Stack>
                                                                )}
                                                                sx={{mx: '2px'}} />
                                                        ) : (
                                                            <Fragment>
                                                                {(Boolean(item.text) && (
                                                                    <InputLabel
                                                                        className={`${Boolean(item.bind) && 'bind'}`}
                                                                        htmlFor={item.name}>
                                                                        {item.text}
                                                                    </InputLabel>
                                                                ))}
                                                                <Item
                                                                    item={item}
                                                                    field={field}
                                                                    setValue={setValue}
                                                                    getValues={getValues} />
                                                            </Fragment>
                                                        )))}
                                                        
                                                        {(item.name.split('.').reduce((data: Hash<any>, name: string) => (data?.[name]), errors) && (
                                                            <FormHelperText>
                                                                {`${item.name.split('.').reduce((data: Hash<any>, name: string) => (data?.[name]), errors).message}`}
                                                            </FormHelperText>
                                                        ))}
                                                    </FormControl>
                                                )} />
                                        </Grid>
                                    )))
                                ))}
                            </Grid>
                            {(Boolean(note) && (
                                <Grid
                                    xs={12}
                                    item>
                                    <Typography
                                        sx={{color: 'text.secondary'}}
                                        variant="body1"
                                        gutterBottom>
                                        {note}
                                    </Typography>
                                </Grid>
                            ))}

                            {((Boolean(save) || Boolean(quit)) && (
                                <Grid
                                    sx={{pt: 4}}
                                    xs={12}
                                    item>
                                    <Stack
                                        justifyContent="end"
                                        direction="row"
                                        spacing={2}>
                                        {(Boolean(quit) && (
                                            <Button
                                                color="secondary"
                                                variant="contained"
                                                disabled={lock}
                                                onClick={(event) => {
                                                    quit?.task();
                                                }}>
                                                {(quit?.text ?? 'Cancel')}
                                            </Button>
                                        ))}

                                        {(Boolean(save) && (
                                            <Button
                                                type="submit"
                                                variant="contained"
                                                disabled={lock}>
                                                {(save?.text ?? 'Submit')}
                                            </Button>
                                        ))}
                                    </Stack>
                                </Grid>
                            ))}
                        </CardContent>
                    </Card>
                </Grid>
            )))}
        </Grid>
    )
}