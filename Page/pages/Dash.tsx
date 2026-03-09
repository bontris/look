import React, {Fragment, useMemo, useState, useEffect} from "react";

import type {Null} from "../types/Null";

import type {Hash} from "../types/Hash";

import {useApplication} from "../hooks/Application";

import {useSession} from "./../hooks/Session";

import {
    Box,
    Chip,
    Card,
    Grid,
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
    Tooltip,
    Backdrop,
    Collapse,
    Snackbar,
    MenuItem,
    ListItem,
    CardMedia,
    InputBase,
    TextField,
    CardHeader,
    Typography,
    IconButton,
    CardContent,
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
    CardActionArea,
    CircularProgress,
} from "@mui/material";

import Carousel from "react-material-ui-carousel";

import {BACK, ROOT, TEAM, SHOP, NAME} from "./../environment";

import Store from "./../services/Dash";

const Plot = ({list, name, hint}: {
    name: string,
    hint: string,
    list: Array<any>
}) => {
    return (
        <Card variant="outlined">
            <CardHeader
                title={name}
                subheader={hint} />
            <CardContent>
                
            </CardContent>
        </Card>
    );
}

export const Dash = () => {
    const {
        setTitle
    } = useApplication();

    const {session} = useSession();

    const [data, setData] = useState<{
        card: Null<Hash<any>>,
        list: Array<any>,
        wait: boolean,
        lock: boolean,
        done: boolean,
        fail: boolean,
    }>({
        wait: false,
        lock: false,
        done: false,
        fail: false,
        card: null,
        list: []
    });

    useEffect(() => {
        Store.home((done, data) => {
            setTimeout(() => {
                if (done) {
                    setData((last) => ({
                        ...last,
                        done: true,
                        list: data.list ?? [],
                        card: data.card ?? null,
                    }));
                } else {
                    setData((last) => (
                        {...last, fail: true}
                    ));
                }
            }, 300);
        });

        setTitle('Inicio');
    }, []);

    const list = useMemo(() => (
        data.list.reduce((hash, item) => {
            hash[item.type].push(item);

            return hash;
        }, {1: [], 3: []})
    ), [data.list]);

    const items = [
        {
            name: "Random Name #1",
            description: "Probably the most random thing you have ever seen!"
        },
        {
            name: "Random Name #2",
            description: "Hello World!"
        }
    ];

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
                    Inicio
                </Typography>
                <Typography
                    sx={(theme) => ({
                        '& b': {
                            color: theme.palette.primary.main
                        }
                    })}
                    color="text.secondary"
                    gutterBottom>
                    Hola <b>{session.name}</b>, te damos la bienvenida a <b>{NAME}</b>.
                </Typography>
            </Grid>
            <Grid
                display="flex"
                flex={1}
                item>
                {(data.done ? (
                    <Grid
                        spacing={2}
                        container>
                        <Grid
                            xs={((session.type == SHOP) ? 9 : 12)}
                            item>
                            <Grid
                                spacing={2}
                                container>
                                {(Boolean(list[3].length) && (
                                    <Grid
                                        xs={12}
                                        item>
                                        <Carousel
                                            navButtonsAlwaysVisible={true}>
                                            {list[3].map((item: any) => (
                                                <Card
                                                    sx={{padding: 2}}
                                                    variant="outlined">
                                                    <CardMedia
                                                        component="img"
                                                        height="560"
                                                        width="100%"
                                                        image={`${BACK}/snaps/${item.snap}`}
                                                        sx={{borderRadius: '8px'}} />
                                                    <CardContent sx={{padding: '16px 0px 0px 0px !important'}}>
                                                        <Grid
                                                            spacing={2}
                                                            container>
                                                            {([item.text, item.note].some(Boolean) && (
                                                                <Grid
                                                                    xs={12}
                                                                    item>
                                                                    {(Boolean(item.text) && (
                                                                        <Typography
                                                                            color="text.primary"
                                                                            variant="h6"
                                                                            gutterBottom>
                                                                            {item.text}
                                                                        </Typography>
                                                                    ))}

                                                                    {(Boolean(item.note) && (
                                                                        <Typography
                                                                            color="text.secondary"
                                                                            gutterBottom>
                                                                            {item.note}
                                                                        </Typography>
                                                                    ))}
                                                                </Grid>
                                                            ))}

                                                            {(Boolean(item.link) && (
                                                                <Grid
                                                                    xs={12}
                                                                    item>
                                                                    <Button
                                                                        href={item.link}
                                                                        color="primary"
                                                                        target="_blank"
                                                                        variant="contained">
                                                                        {(Boolean(item.more) ? item.more : 'Más información')}
                                                                    </Button>
                                                                </Grid>
                                                            ))}
                                                        </Grid>
                                                    </CardContent>
                                                </Card>
                                            ))}
                                        </Carousel>
                                    </Grid>
                                ))}

                                {(Boolean(list[1].length) && (
                                    <Grid
                                        xs={12}
                                        item>
                                        <Grid
                                            spacing={2}
                                            alignItems="stretch"
                                            container>
                                            {list[1].map((item: any) => (
                                                <Grid
                                                    xs={3}
                                                    key={item.item}
                                                    display="flex"
                                                    item>
                                                    <Paper
                                                        sx={{padding: 2, display: 'flex', flex: 1}}
                                                        variant="outlined">
                                                        <Stack
                                                            flex={1}
                                                            gap={1}>
                                                            <Box
                                                                component="img"
                                                                sx={{
                                                                    borderRadius: 2,
                                                                    height: "auto",
                                                                    width: "100%"
                                                                }}
                                                                src={`${BACK}/snaps/${item.snap}`} />

                                                            {([item.text, item.note, item.link].some(Boolean) && (
                                                                <Stack flex={1} gap={2}>
                                                                    <Stack flex={1}>
                                                                        {(Boolean(item.text) && (
                                                                            <Typography
                                                                                color="text.primary"
                                                                                variant="h6"
                                                                                gutterBottom>
                                                                                {item.text}
                                                                            </Typography>
                                                                        ))}

                                                                        {(Boolean(item.note) && (
                                                                            <Typography
                                                                                color="text.secondary"
                                                                                gutterBottom>
                                                                                {item.note}
                                                                            </Typography>
                                                                        ))}
                                                                    </Stack>

                                                                    {(Boolean(item.link) && (
                                                                        <Button
                                                                            href={item.link}
                                                                            color="primary"
                                                                            target="_blank"
                                                                            variant="contained">
                                                                            {(Boolean(item.more) ? item.more : 'Más información')}
                                                                        </Button>
                                                                    ))}
                                                                </Stack>
                                                            ))}
                                                        </Stack>
                                                    </Paper>
                                                </Grid>
                                            ))}
                                        </Grid>
                                    </Grid>
                                ))}
                            </Grid>
                        </Grid>
                        {((session.type == SHOP) && (
                            <Grid
                                xs={3}
                                item>
                                <Grid
                                    spacing={2}
                                    container>
                                    {(Boolean(data.card) && (
                                        <Grid
                                            xs={12}
                                            item>
                                            <Paper
                                                sx={{padding: 2}}
                                                variant="outlined">
                                                <Stack
                                                    flex={1}
                                                    gap={2}>
                                                    <Box
                                                        component="img"
                                                        sx={{
                                                            borderRadius: 2,
                                                            height: "auto",
                                                            width: "100%"
                                                        }}
                                                        src={data.card?.icon} />
                                                    <Stack
                                                        flex={1}
                                                        gap={1}>
                                                        <Stack
                                                            alignItems="center"
                                                            flex={1}>
                                                            <Typography
                                                                color="text.primary"
                                                                variant="h5"
                                                                gutterBottom>
                                                                {data.card?.name} {data.card?.last}
                                                            </Typography>
                                                            <Typography
                                                                color="text.secondary"
                                                                gutterBottom>
                                                                {data.card?.note}
                                                            </Typography>
                                                        </Stack>
                                                        <Stack
                                                            gap={2}
                                                            direction="row"
                                                            justifyContent="center">
                                                            <IconButton
                                                                sx={{padding: 0}}
                                                                href={`mailto:${data.card?.mail}`}>
                                                                <Avatar
                                                                    sx={(theme) => ({
                                                                        width: 42,
                                                                        height: 42,
                                                                        background: ((theme.palette.mode == 'dark') ? '#FFFFFF' : '#000000')
                                                                    })}
                                                                    variant="circular">
                                                                    <SvgIcon sx={{width: '24px', height: '24px'}}>
                                                                        <g
                                                                            strokeLinejoin="round"
                                                                            strokeLinecap="round"
                                                                            strokeWidth="2"
                                                                            stroke="currentColor"
                                                                            fill="none">
                                                                            <path d="M3 7a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                                                                            <path d="m3 7l9 6l9-6" />
                                                                        </g>
                                                                    </SvgIcon>
                                                                </Avatar>
                                                            </IconButton>
                                                            <IconButton
                                                                sx={{padding: 0}}
                                                                href={`tel:${data.card?.work}`}>
                                                                <Avatar
                                                                    sx={(theme) => ({
                                                                        width: 42,
                                                                        height: 42,
                                                                        background: ((theme.palette.mode == 'dark') ? '#FFFFFF' : '#000000')
                                                                    })}
                                                                    variant="circular">
                                                                    <SvgIcon sx={{width: '24px', height: '24px'}}>
                                                                        <g
                                                                            strokeLinejoin="round"
                                                                            strokeLinecap="round"
                                                                            strokeWidth="2"
                                                                            stroke="currentColor"
                                                                            fill="none">
                                                                            <path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2" />
                                                                        </g>
                                                                    </SvgIcon>
                                                                </Avatar>
                                                            </IconButton>
                                                            <IconButton
                                                                sx={{padding: 0}}
                                                                href={`https://api.whatsapp.com/send?phone=${data.card?.work}&text=`}
                                                                target="_blank">
                                                                <Avatar
                                                                    sx={(theme) => ({
                                                                        width: 42,
                                                                        height: 42,
                                                                        background: ((theme.palette.mode == 'dark') ? '#FFFFFF' : '#000000')
                                                                    })}
                                                                    variant="circular">
                                                                    <SvgIcon sx={{width: '24px', height: '24px'}}>
                                                                        <g
                                                                            strokeLinejoin="round"
                                                                            strokeLinecap="round"
                                                                            strokeWidth="2"
                                                                            stroke="currentColor"
                                                                            fill="none">
                                                                            <path d="m3 21l1.65-3.8a9 9 0 1 1 3.4 2.9z" />
                                                                            <path d="M9 10a.5.5 0 0 0 1 0V9a.5.5 0 0 0-1 0za5 5 0 0 0 5 5h1a.5.5 0 0 0 0-1h-1a.5.5 0 0 0 0 1" />
                                                                        </g>
                                                                    </SvgIcon>
                                                                </Avatar>
                                                            </IconButton>
                                                        </Stack>
                                                    </Stack>
                                                    {(Boolean(data.card?.book) && (
                                                                <Stack direction="row" justifyContent="center">
                                                                    <Button
                                                                        href={data.card?.book}
                                                                        startIcon={(
                                                                            <SvgIcon sx={{width: 24, height: 24}}>
                                                                                <g
                                                                                    strokeLinejoin="round"
                                                                                    strokeLinecap="round"
                                                                                    strokeWidth="2"
                                                                                    stroke="currentColor"
                                                                                    fill="none">
                                                                                    <path d="M4 7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2zm12-4v4M8 3v4m-4 4h16" />
                                                                                    <path d="M8 15h2v2H8z" />
                                                                                </g>
                                                                            </SvgIcon>
                                                                        )}
                                                                        endIcon={(
                                                                            <SvgIcon sx={{width: 24, height: 24}}>
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
                                                                        color="primary"
                                                                        target="_blank"
                                                                        variant="contained">
                                                                        Agenda tu cita
                                                                    </Button>
                                                                </Stack>
                                                        ))}
                                                </Stack>
                                            </Paper>
                                        </Grid>
                                    ))}
                                </Grid>
                            </Grid>
                        ))}
                    </Grid>
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
                                        <path d="M4 6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2z" />
                                        <path d="M4 13h3l3 3h4l3-3h3" />
                                    </g>
                                </SvgIcon>
                            </Avatar>
                            <Typography
                                color="text.primary"
                                variant="h4"
                                gutterBottom>
                                Lo sentimos
                            </Typography>
                            <Typography
                                color="text.secondary"
                                gutterBottom>
                                No se pudo cargar la información.
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
};