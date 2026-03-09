import {useState, useEffect} from "react";

import {useTheme} from "@mui/material/styles";

import {
    useForm,
    Controller,
    SubmitHandler
} from "react-hook-form";

import {
    Box,
    Card,
    Grid,
    Link,
    Stack,
    Alert,
    SvgIcon,
    Checkbox,
    CardMedia,
    TextField,
    IconButton,
    InputLabel,
    Typography,
    FormControl,
    InputAdornment,
    FormHelperText,
    FormControlLabel
} from "@mui/material";

import LoadingButton from "@mui/lab/LoadingButton";

import {
    useNavigate,
    useLocation
} from "react-router-dom";

import {WIDE} from "./../environment";

import {useSession} from "./../hooks/Session";

type Properties = {
    term?: boolean,
    data?: boolean,
    name?: string
    pass?: string
}

type Data = {
    term: boolean,
    data: boolean,
    name: string,
    pass: string
}

export const Sign = ({term, data, name, pass}: Properties) => {
    const {sign} = useSession();

    const theme = useTheme();

    const navigate = useNavigate();

    const location = useLocation();

    const [fail, setFail] = useState({
        text: '',
        list: {
            name: '',
            pass: ''
        }
    });

    const [show, setShow] = useState(false);

    const {control, watch, handleSubmit, formState: {errors}} = useForm({
        defaultValues: {
            term: term ?? false,
            data: data ?? false,
            name: name ?? '',
            pass: pass ?? ''
        }
    });

    useEffect(() => {
        setFail((last) => ({...last, list: {...last.list, name: errors.name?.message ?? ''}}));
    }, [errors.name]);

    useEffect(() => {
        setFail((last) => ({...last, list: {...last.list, pass: errors.pass?.message ?? ''}}));
    }, [errors.pass]);

    const onSubmit: SubmitHandler<Data> = async ({term: term, data: data, name: name, pass: pass}: Data) => {
        let {done, next, text, list} = await sign(name, pass);

        setFail({text: '', list: {name: '', pass: ''}});

        if (done) {
            navigate((location.state?.from?.pathname ?? next), {replace: true});
        } else {
            setFail({text, list});
        }
    }

    const form = watch();
    
    return (
        (WIDE ? (
            <Box sx={{flex: 1, height: '100vh', display: 'flex', background: '#F8F7FA'}}>
                <Stack sx={{flex: 1, overflow: 'hidden', objectFit: 'cover'}}>
                    <img
                        src="/images/sign.jpg"
                        style={{
                            flex: 1
                        }} />
                </Stack>
                <Stack sx={{justifyContent: 'center', alignItems: 'center', zIndex: 2, background: theme.palette.background.paper}}>
                    <Box sx={{padding: '0px 48px', width: '480px'}}>
                        <Box
                            component="img"
                            sx={{
                                width: 'auto',
                                height: 85,
                                margin: '0px auto'
                            }}
                            alt="Taller A"
                            src={`/images/${((theme.palette.mode == 'dark') ? 'dark' : 'main')}.png`} />
                        <Grid sx={{my: 4}}>
                            <Typography
                                color="text.primary"
                                variant="h5"
                                gutterBottom>
                                Bienvenido a Taller A
                            </Typography>
                            <Typography
                                color="text.secondary"
                                gutterBottom>
                                Porfavor inicia sesión con tu cuenta para ingresar
                            </Typography>
                        </Grid>
                        {(Boolean(fail?.text) && (
                            <Grid sx={{mb: 2}}>
                                <Alert severity="error">
                                    {fail.text}
                                </Alert>
                            </Grid>
                        ))}
                        <Grid
                            autoComplete="off"
                            component="form"
                            onSubmit={handleSubmit(onSubmit)}
                            spacing={2}
                            sx={{mb: 2}}
                            container
                            noValidate>
                            <Grid
                                xs={12}
                                item>
                                <Controller
                                    name="name"
                                    control={control}
                                    rules={{required: 'El usuario es requerido', pattern: {
                                        value: /(([A-Z\d][A-Z\d_-]){2,16}|\S+@\S+\.\S+)/i,
                                        message: 'El valor ingresado debe ser un nombre de usuario o correo electrónico válido.',
                                    }}}
                                    render={({field}) => (
                                        <FormControl
                                            {...field}
                                            error={Boolean(fail.list.name)}
                                            fullWidth>
                                            <InputLabel htmlFor="name">
                                                Usuario
                                            </InputLabel>
                                            <TextField
                                                id="Name"
                                                value={field.value}
                                                placeholder="Nombre de usuario o correo electrónico" />
                                            {(fail.list.name && (
                                                <FormHelperText>{fail.list.name}</FormHelperText>
                                            ))}
                                        </FormControl>
                                    )} />
                            </Grid>
                            <Grid
                                xs={12}
                                item>
                                <Controller
                                    name="pass"
                                    control={control}
                                    rules={{required: 'La contraseña es requerida'}}
                                    render={({field}) => (
                                    <FormControl
                                        {...field}
                                        error={Boolean(fail.list.pass)}
                                        fullWidth >
                                        <InputLabel htmlFor="pass">
                                            Contraseña
                                        </InputLabel>
                                        <TextField
                                            id="Pass"
                                            type={(show ? 'text' : 'password')}
                                            value={field.value}
                                            placeholder="············"
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
                                                                        <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0-4 0" />
                                                                        <path d="M21 12q-3.6 6-9 6t-9-6q3.6-6 9-6t9 6" />
                                                                    </g>
                                                                ))}
                                                            </SvgIcon>
                                                        </IconButton>
                                                    </InputAdornment>
                                                )
                                            }} />
                                        {(fail.list.pass && (
                                            <FormHelperText>{fail.list.pass}</FormHelperText>
                                        ))}
                                    </FormControl>
                                )} />
                            </Grid>
                            <Grid
                                xs={12}
                                item>
                                <Controller
                                    name="term"
                                    control={control}
                                    rules={{required: 'El campo es requerido.'}}
                                    render={({field}) => (
                                    <FormControl
                                        {...field}
                                        fullWidth >
                                        <FormControlLabel
                                            control={(
                                                <Checkbox />
                                            )}
                                            label={(
                                                <Typography color="text.primary">
                                                    Acepto los&nbsp;
                                                    <Link
                                                        href="https://tallera.co/politicas-de-privacidad"
                                                        target="_blank">
                                                        términos, condiciones y políticas
                                                    </Link>
                                                    &nbsp;de privacidad.
                                                </Typography>
                                            )} />
                                    </FormControl>
                                )} />
                                <Controller
                                    name="data"
                                    control={control}
                                    rules={{required: 'El campo es requerido.'}}
                                    render={({field}) => (
                                    <FormControl
                                        {...field}
                                        fullWidth >
                                        <FormControlLabel
                                            control={(
                                                <Checkbox />
                                            )}
                                            label={(
                                                <Typography color="text.primary">
                                                    Acepto los&nbsp;
                                                    <Link
                                                        href="https://tallera.co/tratamiento-de-datos-app"
                                                        target="_blank">
                                                        términos, condiciones y políticas
                                                    </Link>
                                                    &nbsp;de tratamiento de datos personales.
                                                </Typography>
                                            )} />
                                    </FormControl>
                                )} />
                            </Grid>
                            <Grid 
                                xs={12}
                                sx={{mt: 2}}
                                item>
                                <LoadingButton
                                    size="large"
                                    type="submit"
                                    color="primary"
                                    variant="contained"
                                    disabled={((form.term && form.data) == false)}
                                    loadingPosition="start"
                                    loading={false}
                                    onClick={(event) => {
                                    }}
                                    fullWidth>
                                    Iniciar sesión
                                </LoadingButton>
                            </Grid>
                        </Grid>
                    </Box>
                </Stack>
            </Box>
        ) : (
            <Box sx={{flexGrow: 1, minHeight: '100vh', display: 'flex', background: '#F8F7FA'}}>
                <Stack sx={{flex: 1, alignItems: 'center', justifyContent: 'center'}}>
                    <Card sx={{padding: '32px 32px', width: '420px'}}>
                        <CardMedia
                            image={`/images/${((theme.palette.mode == 'dark') ? 'dark' : 'main')}.png`}
                            sx={{ height: 80}} />
                        <Grid sx={{my: 4}}>
                            <Typography
                                color="text.primary"
                                variant="h5"
                                gutterBottom>
                                Bienvenido a Taller A
                            </Typography>
                            <Typography
                                color="text.secondary"
                                gutterBottom>
                                Porfavor inicia sesión con tu cuenta para ingresar
                            </Typography>
                        </Grid>
                        <Grid
                            autoComplete="off"
                            component="form"
                            onSubmit={handleSubmit(onSubmit)}
                            spacing={2}
                            sx={{mb: 2}}
                            container
                            noValidate>
                            <Grid
                                xs={12}
                                item>
                                <Controller
                                    name="name"
                                    control={control}
                                    rules={{required: 'El usuario es requerido.', pattern: {
                                        value: /\S+@\S+\.\S+/,
                                        message: 'El valor ingresado debe ser un nombre de usuario o correo electrónico válido.',
                                    }}}
                                    render={({field}) => (
                                        <FormControl
                                            {...field}
                                            error={Boolean(fail.list.name)}
                                            fullWidth>
                                            <InputLabel htmlFor="name">
                                                Username
                                            </InputLabel>
                                            <TextField
                                                id="name"
                                                value={field.value}
                                                placeholder="Enter your email or username" />
                                            {(fail.list.name && (
                                                <FormHelperText>{fail.list.name}</FormHelperText>
                                            ))}
                                        </FormControl>
                                    )} />
                            </Grid>
                            <Grid
                                xs={12}
                                item>
                                <Controller
                                    name="pass"
                                    control={control}
                                    rules={{required: 'La contraseña es requerida'}}
                                    render={({field}) => (
                                    <FormControl
                                        {...field}
                                        error={Boolean(fail.list.pass)}
                                        fullWidth >
                                        <InputLabel htmlFor="pass">
                                            Contraseña
                                        </InputLabel>
                                        <TextField
                                            id="pass"
                                            type={(show ? 'text' : 'password')}
                                            value={field.value}
                                            placeholder="············"
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
                                                                        <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0-4 0" />
                                                                        <path d="M21 12q-3.6 6-9 6t-9-6q3.6-6 9-6t9 6" />
                                                                    </g>
                                                                ))}
                                                            </SvgIcon>
                                                        </IconButton>
                                                    </InputAdornment>
                                                )
                                            }} />
                                        {(fail.list.pass && (
                                            <FormHelperText>{fail.list.pass}</FormHelperText>
                                        ))}
                                    </FormControl>
                                )} />
                            </Grid>
                            <Grid
                                xs={12}
                                item>
                                <Controller
                                    name="term"
                                    control={control}
                                    rules={{required: 'El campo es requerido.'}}
                                    render={({field}) => (
                                    <FormControl
                                        {...field}
                                        fullWidth >
                                        <FormControlLabel
                                            control={(
                                                <Checkbox />
                                            )}
                                            label={(
                                                <Typography color="text.primary">
                                                    Acepto los&nbsp;
                                                    <Link
                                                        href="https://tallera.co/politicas-de-privacidad"
                                                        target="_blank">
                                                        términos, condiciones y políticas
                                                    </Link>
                                                    &nbsp;de privacidad.
                                                </Typography>
                                            )} />
                                    </FormControl>
                                )} />
                                <Controller
                                    name="data"
                                    control={control}
                                    rules={{required: 'El campo es requerido.'}}
                                    render={({field}) => (
                                    <FormControl
                                        {...field}
                                        fullWidth >
                                        <FormControlLabel
                                            control={(
                                                <Checkbox />
                                            )}
                                            label={(
                                                <Typography color="text.primary">
                                                    Acepto los&nbsp;
                                                    <Link
                                                        href="https://tallera.co/tratamiento-de-datos-app"
                                                        target="_blank">
                                                        términos, condiciones y políticas
                                                    </Link>
                                                    &nbsp;de tratamiento de datos personales.
                                                </Typography>
                                            )} />
                                    </FormControl>
                                )} />
                            </Grid>
                            <Grid 
                                xs={12}
                                sx={{mt: 2}}
                                item>
                                <LoadingButton
                                    size="large"
                                    type="submit"
                                    color="primary"
                                    variant="contained"
                                    disabled={((form.term && form.data) == false)}
                                    loadingPosition="start"
                                    loading={false}
                                    onClick={(event) => {
                                    }}
                                    fullWidth>
                                    Iniciar sesión
                                </LoadingButton>
                            </Grid>
                        </Grid>
                    </Card>
                </Stack>
            </Box>
        ))
    )
}