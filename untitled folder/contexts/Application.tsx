import {ReactNode, useMemo, useState, useEffect, createContext, ReactElement} from "react";

import useMediaQuery from '@mui/material/useMediaQuery';

import type {AlertColor} from "@mui/material/Alert";

import {NAME, TINT} from "./../environment";

import {
    Grid,
    Stack,
    Alert,
    alpha,
    Dialog,
    Button,
    SvgIcon,
    Snackbar,
    Typography,
    DialogTitle,
    GlobalStyles,
    DialogContent,
    DialogActions
} from "@mui/material";

import {useCookies} from "react-cookie";

import {ThemeProvider, createTheme} from "@mui/material/styles";

import type {Null} from "./../types/Null";

import type {Hash} from "../types/Hash";

type Notification = {
    icon?: ReactNode,
    seen?: boolean,
    hint?: string,
    link?: string,
    text: string,
    date: Date
}

type Message = {
    type?: AlertColor,
    link?: Null<string>,
    text: string
}

type Search = {
    text: string,
    date: Date
}

type Result = {
    text: string,
    icon: ReactNode
}

export const ApplicationProvider = ({children}: {children: ReactNode}) => {
    const [notifications, setNotifications] = useState<Array<Notification>>([{text: 'Bernard Woods', hint: 'You have new message from Bernard Woods', date: new Date(), seen: false}]);

    const dark = useMediaQuery('(prefers-color-scheme: dark)', {noSsr: true});

    const [searches, setSearches] = useState<Array<Search>>([]);

    const [message, setMessage] = useState<Null<Message>>(null);

    const [live, setLive] = useState(window.navigator.onLine);

    const [page, setMain] = useState<{
        icon?: Null<ReactNode>,
        name?: Null<string>,
        text?: Null<string>
        node?: ReactElement
    }>({});

    const [cookies, setCookie] = useCookies([
        'mode',
        'skin',
        'side',
        'code',
        'text'
    ]);

    const [pane, setPane] = useState<Hash<any>>({});

    const [note, setNote] = useState<any>({});

    const [busy, setBusy] = useState(false);

    const [side, setSide] = useState(((cookies.side ?? 'true') == 'true'));

    const [mode, setMode] = useState<'system' | 'light' | 'dark'>((cookies.mode ?? 'light'));

    const [text, setText] = useState<'en' | 'es' | 'fr'>((cookies.text ?? 'es'));

    const [pages, setPages] = useState([]);

    const setDialog = (view: ReactNode, name: string, hint?: string, size?: 'lg' | 'md' | 'sm' | 'xl' | 'xs', done?: Hash<any>) => {
        setPane({
            wait: false,
            open: true,
            name: name,
            hint: hint,
            view: view,
            size: size,
            done: done
        });
    }

    const setAlert = (text: string, type?: Null<AlertColor>, link?: Null<string>) => {
        setNote({
            open: true,
            text: text,
            link: link,
            type: type
        });
    }

    const setTitle = (title: Null<string>) => {
        if ((title = title?.trim() ?? null)) {
            document.title = `${NAME} - ${title}`;
        } else {
            document.title = NAME;
        }
    }

    const setPage = (name: string, text?: string, icon?: ReactNode) => {
        setMain({icon: icon, text: text, name: name});
    }

    const useSearch = async (text: string) => {
        return [];
    }

    const useRoute = (name: string, data?: {[name: string]: string | number}) => {
        return '';
    }

    useEffect(() => {
        window.addEventListener('online', () => {
            setLive(true);
        });

        window.addEventListener('offline', () => {
            setLive(false);
        });
    }, []);

    useEffect(() => {
        setCookie('side', side ? 'true' : 'false');
    }, [side]);

    useEffect(() => {
        setCookie('mode', mode);
    }, [mode]);

    useEffect(() => {
        setCookie('text', text);
    }, [text]);

    const main = useMemo(() => (
        createTheme({
            typography: {
                fontFamily: [
                    'Public Sans',
                    '-apple-system',
                    'BlinkMacSystemFont',
                    'Segoe UI', 
                    'Oxygen',
                    'Ubuntu', 
                    'Cantarell',
                    'Fira Sans',
                    'Droid Sans',
                    'Helvetica Neue',
                    'sans-serif'
                ].join(',')
            },
            components: {
                MuiTypography: {
                    styleOverrides: {
                        subtitle1: {
                            fontSize: '14px',
                            fontWeight: 400,
                            lineHeight: '20px'
                        },
                        body1: {
                            fontSize: '15px',
                            fontWeight: 400,
                            lineHeight: '22px'
                        },
                        h6: {
                            margin: 0,
                            fontSize: '16px',
                            fontWeight: 500,
                            lineHeight: '20px'
                        },
                        h5: {
                            margin: 0,
                            fontSize: '18px',
                            fontWeight: 500,
                            lineHeight: '24px'
                        },
                        h4: {
                            margin: 0,
                            fontSize: '24px',
                            fontWeight: 500,
                            lineHeight: '28px'
                        },
                        h3: {
                            margin: 0,
                            fontSize: '22px',
                            fontWeight: 500,
                            lineHeight: '28px'
                        }
                    }
                },
                MuiCardHeader: {
                    styleOverrides: {
                        title: {
                            margin: 0,
                            fontSize: '18px',
                            fontWeight: 500,
                            lineHeight: '28px'
                        }
                    }
                },
                MuiStepLabel: {
                    styleOverrides: {
                        iconContainer: {
                            
                        }
                    }
                },
                MuiSelect: {
                    styleOverrides: {
                        root: ({theme}) => ({
                            paddingRight: '32px!important'
                        })
                    }
                },
                MuiAppBar: {
                    styleOverrides: {
                        root: ({theme}) => ({
                            top: 0,
                            padding: '0px 0px',
                            position: 'sticky',
                            borderRadius: 0,
                            borderBottom: `1px solid ${alpha(theme.palette.divider, 0.12)}`,
                            backdropFilter: 'blur(8px)',
                            backgroundColor: alpha('#ede9dd', 0.8)
                        })
                    }
                },
                MuiButton: {
                    styleOverrides: {
                        root: ({theme}) => (
                            {
                                padding: '8px 22px',
                                minWidth: 'auto',
                                boxShadow: 'none',
                                borderRadius: 50,
                                textTransform: 'none',
                                '&:hover': {
                                    boxShadow: 'none'
                                },
                                variants: [
                                    {
                                        props: {variant: 'tonal', color: 'primary' },
                                        style: {
                                            backgroundColor: alpha(theme.palette.primary.light, 0.24),
                                            color: theme.palette.primary.main,
                                            '&:not(.Mui-disabled):hover, &:not(.Mui-disabled):active, &.Mui-focusVisible:not(:has(span.MuiTouchRipple-root))':
                                              {
                                                backgroundColor: theme.palette.primary.main
                                              },
                                            '&.Mui-disabled': {
                                              color: theme.palette.primary.main
                                            }
                                        }
                                    }
                                ]
                            }
                        ),
                        containedSecondary: ({theme}) => (
                            {
                                color: theme.palette.secondary.main,
                                background: alpha(theme.palette.secondary.light, 0.16),
                                '&:hover': {
                                    background: alpha(theme.palette.secondary.light, 0.24),
                                },
                                '&.Mui-disabled': {
                                    color: alpha(theme.palette.secondary.main, 0.24)
                                }
                            }
                        )
                    }
                },
                MuiTab: {
                    styleOverrides: {
                        root: {
                            minHeight: 64
                        }
                    }
                },
                MuiCard: {
                    styleOverrides: {
                        root: {
                            boxShadow: '0px 0px 4px 0px rgba(75, 70, 92, 0.1)',
                            borderRadius: '8px',
                            backdropFilter: 'saturate(200%) blur(6px)',
                            ['&.flat']: {
                                background: 'transparent',
                                boxShadow: 'none',
                                borderRadius: '0px',
                                backdropFilter: 'none'
                            }
                        }
                    }
                },
                MuiStep: {
                    styleOverrides: {
                        root: {
                            padding: 0
                        }
                    }
                },
                MuiList: {
                    styleOverrides: {
                        root: {
                            paddingTop: 8,
                            paddingBottom: 8
                        }
                    }
                },
                MuiMenu: {
                    styleOverrides: {
                        root: {
                            borderRadius: 0,
                            paddingTop: 0,
                            paddingBottom: 0
                        },
                        /*list: {
                            borderStyle: 'solid',  
                            borderWidth: 1,
                            borderColor: '#D3D3D3'
                        }*/
                    }
                },
                MuiLink: {
                    styleOverrides: {
                        root: {
                            textDecoration: 'none'
                        }
                    }
                },
                MuiPopover: {
                    styleOverrides: {
                        paper: {
                            borderRadius: 6,
                            boxShadow: '0px 4px 18px rgb( 47 43 61 / 0.16)'
                        }
                    }
                },
                MuiPopper: {
                    styleOverrides: {
                        root: {
                            borderRadius: 6,
                            ['& .shadow'] : {
                                boxShadow: '0 0.25rem 1rem rgba(165, 163, 174, 0.45)'
                            }
                        }
                    },
                },
                MuiToggleButton: {
                    styleOverrides: {
                        root: {
                            padding: '8px'
                        }
                    }
                },
                MuiDialog: {
                    styleOverrides: {
                        paper: {
                            boxShadow: 'rgba(47, 43, 61, 0.16) 0px 4px 18px',
                            borderRadius: '8px',
                            backgroundImage: 'none'
                        }
                    }
                },
                MuiDialogContent:{
                    styleOverrides: {
                        root: {
                            "@media print": {
                                width: '100%',
                                height: '100% !important',
                                overflow: 'visible'
                            }
                        }
                    }
                },
                MuiAvatar: {
                    styleOverrides: {
                        root: ({theme}) => ({
                            fontSize: 15,
                            fontWeight: 400
                        }),
                        img: {
                            objectFit: 'cover'
                        }
                    }
                },
                MuiPaper: {
                    styleOverrides: {
                        root: {
                            borderRadius: 6,
                            ['& .shadow'] : {
                                boxShadow: '0px 4px 18px rgb( 47 43 61 / 0.16)'
                            }
                        }
                    },
                },
                MuiSvgIcon: {
                    styleOverrides: {
                        root: {
                            width: 24,
                            height: 24
                        }
                    }
                },
                MuiTableRow: {
                    styleOverrides:{
                        root: {
                            ['&:last-child > td']: {
                                borderBottom: 0
                            }
                        }
                    }
                },
                MuiListItem: {
                    styleOverrides: {
                        divider: {
                            borderStyle: 'solid',
                            borderColor: 'rgba(0, 0, 0, 0.12)',
                            borderWidth: '1px 0px 0px 0px'
                        }
                    }
                },
                MuiListItemButton: {
                    styleOverrides: {
                        root: {
                            marginTop: 6,
                            marginBottom: 0,
                            minBlockSize: 30,
                            paddingBlock: 8,
                            paddingInline: 12,
                            ['&:hover']: {
                                //background: '#1c1c21'
                            },
                            ['&.Mui-selected']: {
                                //background: '#1c1c21',
                                ['&:hover']: {
                                    //background: '#1c1c21'
                                }
                            },
                            ['&:not(:last-of-type)']: {
                                marginBottom: '6px'
                            }
                        }
                    }  
                },
                MuiMenuItem: {
                    styleOverrides: {
                        root: ({theme}) => ({
                            ['&.Mui-disabled']: {
                                opacity: 1,
                                ['&*']: {
                                    opacity: 0.8
                                }
                            },
                            ['&.Mui-selected']: {
                                //color: theme.palette.primary.main,
                                //background: alpha(theme.palette.primary.light, 0.16),
                                ['&:hover']: {
                                    //background: alpha(theme.palette.primary.light, 0.24),
                                }
                            },
                            ['&:hover']: {
                                //background: theme.palette.action.hover,
                            },
                            ['&:not(:last-of-type)']: {
                                marginBottom: '6px'
                            },
                            paddingBlock: '0.5rem',
                            borderRadius: '6px',
                            marginBottom: '0px',
                            marginRight: '8px',
                            marginLeft: '8px',
                            marginTop: '0px',
                            //color: '#5D596C',
                            gap: '12px'
                        })
                    }
                },
                MuiTableCell: {
                    styleOverrides: {
                        root: {
                            padding: '8px 24px',
                            ['&:focus']: {
                                outline: 'rgb(25, 118, 210) solid 1px'
                            }
                        },
                        head: {
                            //color: '#5D596D',
                            padding: '12px 24px',
                            fontSize: '13px',
                            lineHeight: '22px',
                            textTransform: 'uppercase'
                        }
                    }
                },
                MuiPaginationItem: {
                    styleOverrides: {
                        root: {
                            padding: 18
                        }
                    }
                },
                MuiListItemIcon: {
                    styleOverrides: {
                        root: {
                            minWidth: 30
                        }
                    }
                },
                MuiListItemText: {
                    styleOverrides: {
                        root: {
                            marginTop: 0,
                            marginBottom: 0
                        }
                    }
                },
                MuiOutlinedInput: {
                    styleOverrides: {
                        root: {
                            padding: '8px 16px !important',
                            borderRadius: 6
                        }
                    }
                },
                MuiInputBase: {
                    styleOverrides: {
                        input: {
                            padding: '0px 0px !important'
                        }
                    }
                },
                MuiInputLabel: {
                    styleOverrides: {
                        root: ({theme}) => (
                            {
                                color: theme.palette.text.primary,
                                width: 'fit-content',
                                transform: 'none',
                                maxWidth: '100%',
                                fontSize: '13px',
                                marginBottom: '6px',
                                lineHeight: '20px',
                                position: 'relative'
                            }
                        )
                    }
                },
                MuiFormHelperText: {
                    styleOverrides: {
                        root: {
                            marginLeft: 3
                        }
                    }
                },
                MuiAutocomplete: {
                    styleOverrides: {
                        paper: {
                            backgroundImage: 'linear-gradient(rgba(255, 255, 255, 0.12), rgba(255, 255, 255, 0.12))',
                            borderRadius: 6,
                            boxShadow: '0px 4px 18px rgb( 47 43 61 / 0.16)'
                        },
                        input: {
                            marginRight: '24px'
                        },
                        option: ({theme}) => ({
                            ['&.Mui-disabled']: {
                                opacity: 1,
                                ['&*']: {
                                    opacity: 0.8
                                }
                            },
                            ['&[aria-selected=true]']: {
                                //color: theme.palette.primary.main,
                                //background: alpha(theme.palette.primary.light, 0.16),
                                ['&:hover']: {
                                    //background: alpha(theme.palette.primary.light, 0.24),
                                }
                            },
                            ['&:hover']: {
                                //background: theme.palette.action.hover,
                            },
                            ['&:not(:last-of-type)']: {
                                marginBottom: '6px'
                            },
                            paddingBlock: '0.5rem',
                            borderRadius: '6px',
                            marginBottom: '0px',
                            marginRight: '8px',
                            marginLeft: '8px',
                            marginTop: '0px',
                            //color: '#5D596C',
                            gap: '12px'
                        })
                    }
                },
                MuiFormLabel: {
                    styleOverrides: {
                        root: {
                            ['&.bind:after']: {
                                content: '"*"',
                                margin: '0px 4px',
                                color: '#FF1744'
                            }
                        }
                    }
                },
                MuiTooltip: {
                    styleOverrides: {
                        tooltip: {
                            //background: '#3B3B3B'
                        },
                        arrow: {
                            //borderColor: '#3B3B3B'
                        }
                    }
                },
                MuiChip: {
                    styleOverrides: {
                        root: {
                            height: 24,
                            padding: '2px 8px',
                            fontSize: '13px',
                            borderRadius: '16px'
                        }
                    }
                }
            },
            palette: {
                background: {
                    default: (((mode == 'system') && dark) || (mode == 'dark')) ? '#19191B' : '#FFFFFF',
                    paper: (((mode == 'system') && dark) || (mode == 'dark')) ? '#19191B' : '#FFFFFF'
                },
                primary: {
                    contrastText: '#FFFFFF',
                    main: TINT
                },
                secondary: {
                    main: '#818390'
                },
                success: {
                    contrastText: '#FFFFFF',
                    main: '#28B76F'
                },
                error: {
                    main: '#FF1744'
                },
                text: {
                    secondary: '#64626B'
                },
                mode: (((mode == 'system') && dark) || (mode == 'dark')) ? 'dark' : 'light'
            },
            shape: {
                borderRadius: 6
            }
        })
    ), [dark, mode]);

    return (
        <ApplicationContext.Provider value={{
            setMessage: (
                text: Null<string>,
                type?: AlertColor,
                link?: string
            ) => {
                if (text) {
                    setMessage({
                        type: type ?? 'info',
                        text: text,
                        link: link
                    });
                } else {
                    setMessage(null);
                }
            },
            useSearch,
            useRoute,
            setDialog,
            setAlert,
            setTitle,
            setPage,
            setSide,
            setMode,
            setText,
            notifications,
            searches,
            message,
            pages,
            live,
            busy,
            side,
            page,
            mode,
            text
        }}>
            <ThemeProvider theme={main}>
                <GlobalStyles styles={{
                    body: {
                        background: (((mode == 'system') && dark) || (mode == 'dark')) ? '#121212' : '#F1F3F5',
                        padding: 0,
                        margin: 0
                    }
                }} />
                {children}
                <Snackbar
                    open={note.open}
                    anchorOrigin={{
                        vertical: 'top',
                        horizontal: 'center'
                    }}
                    autoHideDuration={3000}
                    onClose={() => {
                        setNote({...note, open: false});
                    }}>
                    <Alert
                        elevation={6}
                        severity={note.type}
                        variant="filled"
                        sx={{width: '100%'}}
                        onClose={(event) => {
                            setNote({...note, open: false});
                        }}>
                        {note.text}
                    </Alert>
                </Snackbar>
                <Dialog
                    sx={(theme) => (
                        {
                            '& .MuiDialog-paper': {
                                overflow: 'visible'
                            },
                            '& .MuiDialogContent-root': {
                                padding: theme.spacing(2),
                            }
                        }
                    )}
                    open={(pane.open ?? false)}
                    scroll="paper"
                    maxWidth={(pane.size ?? 'md')}
                    onClose={(event) => {
                        setPane((last) => ({
                            ...last,
                            open: false
                        }));
                    }}
                    disableEscapeKeyDown
                    fullWidth>
                    <Button 
                        sx={(theme) => (
                            {
                                top: 0,
                                right: -8,
                                position: 'absolute',
                                boxShadow: 'rgba(47, 43, 61, 0.1) 0px 1px 6px',
                                transform: 'translate(9px, -10px)',
                                borderRadius: '4px',
                                backgroundColor: theme.palette.background.paper,
                                transition: 'transform 0.25s ease-in-out, box-shadow 0.25s ease-in-out',
                                blockSize: 30,
                                inlineSize: 30,
                                minInlineSize: 0,
                                padding: 0,
                                stroke: theme.palette.action.disabled,
                                '&:hover, &:active': {
                                    background: theme.palette.background.paper,
                                    stroke: theme.palette.action.active
                                }
                            }
                        )}
                        disabled={(pane.wait ?? false)}
                        onClick={(event) => {
                            setPane((last: any) => ({
                                ...last,
                                open: false
                            }));
                        }}
                        disableRipple>
                        <SvgIcon sx={{width: '18px', height: '18px'}}>
                            <g
                                strokeLinejoin="round"
                                strokeLinecap="round"
                                strokeWidth="2">
                                <path d="M18 6L6 18M6 6l12 12" />
                            </g>
                        </SvgIcon>
                    </Button>
                    <DialogTitle
                        sx={{px: 3, pt: 4, pb: 2}}
                        alignItems="center"
                        justifyContent="center">
                        <Typography
                            color="text.primary"
                            variant="h5"
                            gutterBottom>
                            {pane.name}
                        </Typography>
                        {(Boolean(pane.hint) && (
                            <Typography
                                color="text.secondary"
                                gutterBottom>
                                {pane.hint}
                            </Typography>
                        ))}
                    </DialogTitle>
                    <DialogContent sx={{px: 3, pb: 3}}>
                        <Grid
                            spacing={2}
                            container>
                            <Grid
                                xs={12}
                                item>
                                {pane.view}
                            </Grid>
                        </Grid>
                    </DialogContent>
                    {(Boolean(pane.done) && (
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
                                            disabled={pane.wait}
                                            onClick={(event) => {
                                                setPane((last) => ({
                                                    ...last,
                                                    open: false
                                                }));
                                            }}>
                                            Cancelar
                                        </Button>
                                        <Button
                                            type="submit"
                                            color={(pane.done?.type ?? 'info')}
                                            variant="contained"
                                            disabled={pane.wait}
                                            onClick={(event) => {
                                                pane.done.task((wait: boolean) => {
                                                    setPane((last) => ({
                                                        ...last,
                                                        wait: wait
                                                    }));
                                                }, (hide: boolean) => {
                                                    setPane((last) => ({
                                                        ...last,
                                                        open: hide ? false : true
                                                    }));
                                                })
                                            }}>
                                            {(pane.done?.text ?? 'Aceptar')}
                                        </Button>
                                    </Stack>
                                </Grid>
                            </Grid>
                        </DialogActions>
                    ))}
                </Dialog>
            </ThemeProvider>
        </ApplicationContext.Provider>
    );
}

export const ApplicationContext = createContext<{
    setMessage: (
        text: Null<string>,
        type?: AlertColor,
        link?: string
    ) => void,
    useSearch: (
        text: string
    ) => Promise<Array<Result>>,
    useRoute: (
        name: string,
        data?: {[name: string]: string | number}
    ) => string,
    setDialog: (
        view: ReactNode,
        name: string,
        hint?: string,
        size?: 'lg' | 'md' | 'sm' | 'xl' | 'xs',
        done?: {
            text?: string,
            type?: 'success' | 'warning' | 'error' | 'info',
            task: (wait: (flag: boolean) => void, hide: (flag: boolean) => void) => void
        }
    ) => void,
    setAlert: (
        text: string,
        type?: AlertColor,
        link?: string
    ) => void,
    setTitle: (
        title: string | null
    ) => void,
    setPage: (
        name: string,
        text?: string,
        icon?: ReactNode
    ) => void,
    setSide: (
        show: boolean
    ) => void,
    setMode: (
        mode: 'system' | 'light' | 'dark'
    ) => void,
    setText: (
        text: 'en' | 'es' | 'fr'
    ) => void,
    notifications: Array<Notification>,
    searches: Array<Search>,
    message: Null<Message>,
    pages: Array<{
        icon: ReactNode,
        node: ReactNode,
        name: string,
        path: string
    }>,
    page: {
        icon?: Null<ReactNode>,
        name?: Null<string>,
        text?: Null<string>,
        node?: ReactElement
    },
    mode: 'system' | 'light' | 'dark',
    text: 'en' | 'es' | 'fr',
    live: boolean,
    busy: boolean,
    side: boolean
} | null>(null)