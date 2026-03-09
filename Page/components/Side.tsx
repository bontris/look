import React, {memo, useState, useEffect, createRef} from "react";

import {
    Link,
    Outlet,
    useNavigate,
    useLocation
} from "react-router-dom";

import {
    Box,
    List,
    alpha,
    Stack,
    Paper,
    Avatar,
    Drawer,
    lighten,
    Divider,
    Toolbar,
    SvgIcon,
    Tooltip,
    Collapse,
    CardMedia,
    InputBase,
    IconButton,
    Typography,
    ListItemIcon,
    ListItemText,
    ListSubheader,
    ListItemButton,
    InputAdornment
} from "@mui/material";

import {SIDE} from "./../environment";

const Item = memo(({left, icon, path, text, name, page, help, ...rest} : {left: number, icon: Array<string>, text: string, page: string, name: string, help: string, path: string | Function}) => {
    return ((path instanceof Function) ? (
        <ListItemButton
            sx={{
                pl: left,
                '&.Mui-selected': {
                    color: 'var(--mui-palette-primary-contrastText);',
                    background: 'linear-gradient(270deg, rgb(var(--mui-palette-primary-mainChannel) / 0.7) 0%, var(--mui-palette-primary-main) 100%) !important;',
                    boxShadow: 'var(--mui-customShadows-primary-sm);'
                }
            }}
            onClick={(event) => {
                path(event);
            }}
            selected={page.includes(text)}
            disableRipple>
            <ListItemIcon>
                <SvgIcon sx={{width: '22px', height: '22px'}}>
                    <g
                        strokeLinejoin="round" 
                        strokeLinecap="round"
                        strokeWidth="2"
                        stroke="currentColor"
                        fill="none">{icon.map((path) => (<path d={path} />))}</g>
                </SvgIcon>
            </ListItemIcon>
            <ListItemText primary={text} />
        </ListItemButton>
    ) : (
        <ListItemButton
            to={path}
            sx={(theme) => ({
                pl: left,
                color: 'text.secondary',
                '& .MuiListItemIcon-root .MuiAvatar-root svg g': {
                    stroke: ((theme.palette.mode == 'dark') ? '#FFFFFF' : '#000000')
                },
                '&:hover': {
                    background: 'none',
                    color: theme.palette.primary.main
                },
                '&:hover .MuiListItemIcon-root .MuiAvatar-root': {
                    background: theme.palette.primary.main
                },
                '&:hover .MuiListItemIcon-root .MuiAvatar-root svg g': {
                    stroke: '#FFFFFF'
                },
                '&.Mui-selected': {
                    color: '#9a9cae',
                    background: 'linear-gradient(72.47deg, #7367f0 22.16%, rgba(115, 103, 240, 0.7) 76.47%)',
                    boxShadow: 'rgba(115, 103, 240, 0.3) 0px 2px 6px',
                    '& *': {
                        color: '#FFFFFF',
                        stroke: '#FFFFFF'
                    },
                    '&:hover': {
                        color: theme.palette.primary.main
                    },
                    
                }
            })}
            selected={page.includes(text)}
            component={Link}
            disableRipple>
            <ListItemIcon>
                <Avatar
                    sx={(theme) => ({
                        width: 32,
                        height: 32,
                        background: alpha(theme.palette.primary.main, 0.16)
                    })}
                    variant="circular">
                    <SvgIcon sx={{width: '20px', height: '20px'}}>
                        <g
                            strokeLinejoin="round" 
                            strokeLinecap="round"
                            strokeWidth="2"
                            fill="none">{icon.map((path) => (<path d={path} />))}</g>
                    </SvgIcon>
                </Avatar>
            </ListItemIcon>
            <ListItemText
                sx={{ml: 1}}
                primary={(
                    <Stack
                        justifyContent="space-between"
                        alignItems="center"
                        direction="row">
                        <Typography>
                            {text}
                        </Typography>
                        {(Boolean(help) && (
                            <Tooltip
                                placement="right"
                                title={help}
                                arrow>
                                <SvgIcon sx={{width: '24px', height: '24px'}}>
                                    <g
                                        strokeLinejoin="round" 
                                        strokeLinecap="round"
                                        strokeWidth="2"
                                        stroke="currentColor"
                                        fill="none">
                                        <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0-18 0m9 4v.01" />
                                        <path d="M12 13a2 2 0 0 0 .914-3.782a1.98 1.98 0 0 0-2.414.483" />
                                    </g>
                                </SvgIcon>
                            </Tooltip>
                        ))}
                    </Stack>
                )} />
        </ListItemButton>
    ));
})

const Menu = memo(({open, knob, left, icon, text, list, fold, page, ...rest}: {open: boolean, knob: boolean, left: number, page: string,  icon: Array<string>, text: string, name: string, list: Array<any>, fold: (open: boolean) => void}) => {
    const [more, setMore] = useState(list.some((item) => (page.includes(item.name))));

    return (
        <React.Fragment>
            {knob ? (
                <ListItemButton
                    sx={{pl: left}}
                    selected={more}
                    onClick={() => {
                        if (knob) {
                            setMore((more ? false : true));

                            fold((more ? false : true));
                        }
                    }}
                    disableRipple>
                    <ListItemIcon sx={{width: 18, height: 18}}>
                        <SvgIcon sx={{width: '22px', height: '22px'}}>
                            <g
                                strokeLinejoin="round" 
                                strokeLinecap="round"
                                strokeWidth="2"
                                stroke="currentColor"
                                fill="none">{icon.map((path) => (<path d={path} />))}</g>
                        </SvgIcon>
                    </ListItemIcon>
                    <ListItemText
                        sx={{fontWeight: open ? 700 : 300}}
                        primary={text} />
                    <SvgIcon>
                        <path
                            strokeLinejoin="round" 
                            strokeLinecap="round"
                            strokeWidth="2"
                            stroke="currentColor"
                            fill="none"
                            d={more ? 'M7.41,15.41L12,10.83L16.59,15.41L18,14L12,8L6,14L7.41,15.41Z' : 'M7.41,8.58L12,13.17L16.59,8.58L18,10L12,16L6,10L7.41,8.58Z'} />
                    </SvgIcon>
                </ListItemButton>
            ) : (
                <ListSubheader sx={(theme) => ({pl: 2, color: theme.palette.primary.main, fontSize: 18, fontWeight: 500})}>
                    {text}
                </ListSubheader>
            )}
            <Collapse
                in={(knob ? more : true)}
                timeout="auto"
                unmountOnExit>
                <List
                    component="div"
                    sx={{paddingTop: 0, paddingBottom: 0}}>
                    {(list.map((item, key) => {
                        if (item.list) {
                            return (
                                <Menu
                                    key={key}
                                    left={4}
                                    page={page}
                                    knob={item.knob}
                                    open={item.open}
                                    icon={item.icon}
                                    text={item.text}
                                    name={item.name}
                                    list={item.list}
                                    fold={(open) => (item.open = open)} />
                            )
                        } else {
                            return (
                                <Item
                                    key={key}
                                    left={4}
                                    page={page}
                                    icon={item.icon}
                                    text={item.text}
                                    path={item.path}
                                    name={item.name}
                                    help={item.help} />   
                            )
                            
                        }
                    }))}
                </List>
            </Collapse>
        </React.Fragment>
    );
})

declare type Data = {
    path: string,
    open: boolean;
    menu: Array<any>;
}

export const Side = ({open, menu, path}: Data) => {
    const [text, setText] = useState<null | string>(null);

    const [list, setList] = useState(menu);

    const location = useLocation();

    useEffect(() => {
        setList(((task: Function, wipe: Function, text: null | string, menu, list) => {
            if ((text = text?.trim() ?? null)) {
                let find = new RegExp(wipe(text)?.replace(/[\-\[\]\{\}\(\)\*\+\?\.\,\\\^\$\|\#]/g, '\\$&'), 'iu');

                menu.forEach((item) => {
                    task(task, wipe, find, item, list);
                });

                return list;
            }

            return menu;
        })((task: Function, wipe: Function, find: RegExp, item: any, list: Array<any>) => {
            if (item.list) {
                let nest: Array<any> = [];

                item.list.forEach((item: any) => {
                    task(task, wipe, find, item, nest);
                });

                if (nest.length) {
                    list.push({...item, list: nest, more: true});
                } else {
                    if (find.test(wipe(item.text))) {
                        list.push({...item, more: false});
                    } else {
                        if (item.more) {
                            delete item.more;
                        }
                    }
                }
            } else {
                if (find.test(wipe(item.name))) {
                    list.push(item);
                }
            }
        }, (text: string) => {
            return text?.replace(/[àáâäæãåāèéêëēėęîïíīįìôöòóœøōõûüùúūçñ]/ig, (item: string) => {
                switch (item.toLowerCase()) {
                  case 'à':
                  case 'á':
                  case 'â':
                  case 'ä':
                  case 'æ':
                  case 'ã':
                  case 'å':
                  case 'ā':
                    return 'a';
                  case 'è':
                  case 'é':
                  case 'ê':
                  case 'ë':
                  case 'ē':
                  case 'ė':
                  case 'ę':
                    return 'e';
                  case 'î':
                  case 'ï':
                  case 'í':
                  case 'ī':
                  case 'į':
                  case 'ì':
                    return 'i';
                  case 'ô':
                  case 'ö':
                  case 'ò':
                  case 'ó':
                  case 'œ':
                  case 'ø':
                  case 'ō':
                  case 'õ':
                    return 'o';
                  case 'û':
                  case 'ü':
                  case 'ù':
                  case 'ú':
                  case 'ū':
                    return 'u';
                  case 'ç':
                    return 'c';
                  case 'ñ':
                    return 'n';
                }

                return item;
            })
        }, text, menu, []))
    }, [text]);

    return (
        <Drawer
            sx={{
                width: open ? SIDE : 0,
                flexShrink: 0,
                '& .MuiDrawer-paper': {
                    width: SIDE,
                    borderRight: '1px solid',
                    borderColor: 'divider',
                    borderRadius: 0
                }
            }}
            open={open}
            anchor="left"
            variant="persistent">
            <Box sx={{
                mt: '54px',
                flexGrow: 1,
                overflow: 'auto'
            }}>
                <Box sx={{pt: 2, px: 2}}>
                    <InputBase
                        sx={(theme) => ({
                            pt: 1,
                            pl: 2,
                            pr: 1,
                            pb: 1,
                            background: alpha(theme.palette.secondary.light, 0.16),
                            borderRadius: theme.shape.borderRadius
                        })}
                        value={(text ?? '')}
                        placeholder="Buscar"
                        startAdornment={
                            <InputAdornment
                                sx={{mr: 1}}
                                position="start">
                                <SvgIcon sx={{width: '22px', height: '22px'}}>
                                    <path
                                        strokeLinejoin="round"
                                        strokeLinecap="round"
                                        strokeWidth="2"
                                        stroke="currentColor"
                                        fill="none"
                                        d="M3 10a7 7 0 1 0 14 0a7 7 0 1 0-14 0m18 11l-6-6"/>
                                </SvgIcon>
                            </InputAdornment>
                        }
                        endAdornment={
                            <IconButton
                                sx={{margin: '0px 0px 0px 8px', padding: '4px', visibility: (Boolean(text) ? 'visible' : 'hidden')}}
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
                        }
                        onChange={(event) => {
                            setText(event.target.value);
                        }}
                        fullWidth />
                </Box>
                <Box sx={{overflow: 'auto'}}>
                    <List component="nav">
                        {(list.map((item, key) => {
                            if (item.list) {
                                return (
                                    <Menu
                                        key={key}
                                        left={2}
                                        page={location.pathname}
                                        knob={item.knob}
                                        icon={item.icon}
                                        text={item.text}
                                        name={item.name}
                                        list={item.list}
                                        open={(item.more || item.open)}
                                        fold={(open) => (item.open = open)} />
                                )
                            } else {
                                return (
                                    <Item
                                        key={key}
                                        left={2}
                                        page={location.pathname}
                                        icon={item.icon}
                                        text={item.text}
                                        path={item.path}
                                        name={item.name}
                                        help={item.help} />
                                )
                            }
                        }))}
                    </List>
                </Box>
            </Box>
        </Drawer>
    );
}