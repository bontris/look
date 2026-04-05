import React, {useState, useEffect} from "react";

import {
    useNavigate
} from "react-router-dom";

import {
    Box,
    Grid,
    Stack,
    Button,
    SvgIcon,
    TextField,
    Typography
} from "@mui/material";

import {BACK} from "./../environment";

import {useApplication} from "../hooks/Application";

import type {Null} from "../types/Null";

import type {Hash} from "../types/Hash";

import {Data} from "./../components/Data";

import Store from "./../services/Banks";

export namespace Banks {
    export const List = () => {
        const {
            setAlert,
            setTitle,
            setDialog
        } = useApplication();

        const [data, setData] = useState({
            find: null as Null<string>,
            sort: {} as Hash<boolean>,
            wait: true,
            take: 16,
            page: 0,
            size: 0,
            time: 0,
            list: [],
            pipe: {} as Hash<{data: Null<any>, sign: '~' | '=' | '<' | '>' | '!'}>
        });

        useEffect(() => {
            Store.load(data.take, data.page, data.find, Object.keys(data.pipe).filter((name) => ((typeof data.pipe[name]['data'] == 'number') || Boolean(data.pipe[name]['data']))).map((name) => {
                return `${name}: ${(data.pipe[name]['sign'])} ${(data.pipe[name]['data'] instanceof Array ? data.pipe[name]['data'].join(' ') : data.pipe[name]['data'])}`
            }, []).join(', '), null, data.sort, (done, data) => {
                if (done) {
                    setData((last) => ({...last, wait: false, page: data.page, list: data.data, size: data.size}));
                } else {
                    setData((last) => ({...last, list: [], wait: false}));
                }
            });
        }, [data.page, data.take, data.sort, data.find, data.pipe, data.time]);

        useEffect(() => {
            setTitle('Tokens');
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
                        Ajuste de Tokens
                    </Typography>
                    <Typography
                        color="text.secondary"
                        gutterBottom>
                        Gestionar los tokens de las empresas
                    </Typography>
                </Grid>
                <Grid
                    display="flex"
                    flex={1}
                    item>
                    <Data
                        seek={'item'}
                        name=""
                        menu={[]}
                        dash={[
                            {
                                icon: ['M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0c1.172-.879 1.172-2.303 0-3.182c-1.171-.879-3.07-.879-4.242 0L12 14.5'],
                                hint: 'Agregar Tokens',
                                name: 'Bank',
                                task: (item: any) => {
                                    let amount = 0;

                                    setDialog(
                                        <Stack direction="column" spacing={2}>
                                            <Typography gutterBottom>
                                                Agregar tokens a <b>{item.name}</b>
                                            </Typography>
                                            <Typography variant="body2" color="text.secondary">
                                                Saldo actual: <b>{item.bank ?? 0}</b> tokens
                                            </Typography>
                                            <TextField
                                                type="number"
                                                label="Cantidad de tokens"
                                                fullWidth
                                                size="small"
                                                inputProps={{min: 1}}
                                                onChange={(e) => {
                                                    amount = parseInt(e.target.value) || 0;
                                                }}
                                            />
                                        </Stack>,
                                        'Agregar Tokens',
                                        'Agregar tokens a la empresa.',
                                        'sm',
                                        {
                                            type: 'success',
                                            text: 'Agregar',
                                            task: (wait: (flag: boolean) => void, hide: (flag: boolean) => void) => {
                                                if (amount < 1) {
                                                    setAlert('La cantidad debe ser mayor a 0.', 'error');
                                                    return;
                                                }

                                                Store.bank(item.hash, amount, (done: boolean, data: any) => {
                                                    hide(true);

                                                    if (done) {
                                                        setData((last) => ({
                                                            ...last,
                                                            time: Date.now()
                                                        }));

                                                        setAlert(data?.text ?? 'Tokens agregados con éxito.', 'success');
                                                    } else {
                                                        setAlert(data?.text ?? 'No se pudieron agregar los tokens.', 'error');
                                                    }
                                                });

                                                wait(true);
                                            }
                                        }
                                    );
                                }
                            }
                        ]}
                        data={[
                            {
                                item: 'name',
                                name: 'Empresa',
                                show: true,
                                sort: true,
                                cast: (item: any) => (
                                    <Stack
                                        sx={{alignItems: 'center'}}
                                        spacing={1}
                                        direction="row">
                                        <Stack
                                            sx={{overflow: 'hidden'}}
                                            direction="column">
                                            <Typography
                                                color={(theme) => (theme.palette.text.primary)}
                                                noWrap>
                                                {item.name}
                                            </Typography>
                                            <Typography
                                                variant="body2"
                                                color={(theme) => (theme.palette.text.secondary)}
                                                noWrap>
                                                {item.card ?? item.code}
                                            </Typography>
                                        </Stack>
                                    </Stack>
                                )
                            },
                            {
                                item: 'bank',
                                name: 'Tokens',
                                edge: 'center',
                                show: true,
                                sort: true,
                                size: 180,
                                cast: (item: any) => (
                                    <Typography
                                        fontWeight={600}
                                        color={item.bank > 0 ? 'success.main' : 'error.main'}>
                                        {item.bank ?? 0}
                                    </Typography>
                                )
                            }
                        ]}
                        take={{
                            pick: data.take ?? 16,
                            list: [16, 32, 64]
                        }}
                        find={{
                            hint: 'Buscar empresa',
                            text: data.find
                        }}
                        none={{
                            text: 'Sin registros',
                            note: 'No se encontraron empresas disponibles.'
                        }}
                        wait={data.wait}
                        list={data.list}
                        size={data.size}
                        page={data.page}
                        load={(page, take, find, pipe, sort) => {
                            setData((last) => ({...last, page: page ?? 1, take: take ?? 16, sort: sort, find: find, pipe: pipe, wait: true}));
                        }} />
                </Grid>
            </Grid>
        );
    }
}
