import {ReactNode, useState, useEffect, createContext} from "react";

import axios from "axios";

import {BACK} from "./../environment";

import type {Null} from "./../types/Null";

import type {Hash} from "./../types/Hash";

export type Session = {
    item: number,
    type: number,
    link: number,
    idle: number,
    time?: Null<number>,
    plan?: Null<object>,
    role?: Null<object>,
    hash?: Null<string>,
    code?: Null<string>,
    nick?: Null<string>,
    face?: Null<string>,
    name?: Null<string>,
    last?: Null<string>,
    mail?: Null<string>,
    work?: Null<string>,
    heap?: Null<Hash<any>>,
    firm?: Null<Hash<any>>
}

type ContextProperties = {
    session: Session,
    sign: (name: string, pass: string) => Promise<any>,
    help: (text: string) => Promise<Hash<any>>,
    ring: (type: number) => Promise<Hash<any>>,
    ping: (pass: string) => Promise<any>,
    exit: (pass: string) => Promise<any>,
    lock?: () => Promise<boolean>,
    quit?: () => Promise<boolean>
}

type ProviderProperties = {
    session?: Session
    children: ReactNode
}

export const SessionProvider = (properties: ProviderProperties) => {
    const [session, setSession] = useState<Session>(properties.session ?? {
        item: 0,
        type: 0,
        link: 0,
        idle: 0,
        plan: {},
        role: {},
        heap: {},
        name: null,
        last: null
    });

    const sign = async (name: string, pass: string) => {
        try {
            let sign = await axios.post(`${BACK}/sign`, {name: name, pass: pass, code: true});

            axios.defaults.headers.common = {
                'Authorization': `${sign.data.pass}`
            };

            let ping = await axios.post(`${BACK}/ping`);

            localStorage.setItem('pass', sign.data.pass);

            setSession({
                item: ping.data.item,
                type: ping.data.type,
                link: ping.data.link,
                time: ping.data.time,
                idle: ping.data.tdle,
                hash: ping.data.hash,
                code: ping.data.code,
                nick: ping.data.nick,
                mail: ping.data.mail,
                work: ping.data.work,
                face: ping.data.face,
                last: ping.data.last,
                name: ping.data.name,
                firm: ping.data.firm
            });

            axios.post(`${BACK}/data`).then(({data}) => {
                setSession({
                    item: ping.data.item,
                    type: ping.data.type,
                    link: ping.data.link,
                    time: ping.data.time,
                    idle: ping.data.tdle,
                    hash: ping.data.hash,
                    code: ping.data.code,
                    nick: ping.data.nick,
                    mail: ping.data.mail,
                    work: ping.data.work,
                    face: ping.data.face,
                    last: ping.data.last,
                    name: ping.data.name,
                    firm: ping.data.firm,
                    heap: data
                });
            });

            return {
                done: true,
                next: '/'
            };
        } catch (fail: any) {
            return {
                done: false,
                list: fail?.response?.data?.list ?? {},
                text: fail?.response?.data?.text ?? null
            };
        }
    }

    const ping = async (pass: string) => {
        try {
            axios.defaults.headers.common = {
                'Authorization': `${pass}`
            };

            let ping = await axios.post(`${BACK}/ping`);

            setSession({
                item: ping.data.item,
                type: ping.data.type,
                link: ping.data.link,
                time: ping.data.time,
                idle: ping.data.tdle,
                hash: ping.data.hash,
                code: ping.data.code,
                nick: ping.data.nick,
                mail: ping.data.mail,
                work: ping.data.work,
                face: ping.data.face,
                last: ping.data.last,
                name: ping.data.name,
                firm: ping.data.firm
            });

            axios.post(`${BACK}/data`).then(({data}) => {
                setSession({
                    item: ping.data.item,
                    type: ping.data.type,
                    link: ping.data.link,
                    time: ping.data.time,
                    idle: ping.data.tdle,
                    hash: ping.data.hash,
                    code: ping.data.code,
                    nick: ping.data.nick,
                    mail: ping.data.mail,
                    work: ping.data.work,
                    face: ping.data.face,
                    last: ping.data.last,
                    name: ping.data.name,
                    firm: ping.data.firm,
                    heap: data
                });
            });

            return {
                done: true,
                next: '/'
            };
        } catch (fail: any) {
            return {
                done: false,
                text: fail?.response?.data?.text ?? null
            };
        }
    }

    const exit = async (pass: string) => {
        try {
            await axios.post(`${BACK}/exit`);

            axios.defaults.headers.common = {};

            setSession({
                item: 0,
                type: 0,
                link: 0,
                idle: 0,
                time: null,
                plan: null,
                role: null,
                hash: null,
                code: null,
                nick: null,
                face: null,
                name: null,
                last: null,
                mail: null,
                work: null,
                firm: null,
                heap: null
            });

            return {
                done: true,
                next: '/login'
            };
        } catch (fail: any) {
            return {
                done: false,
                text: fail?.response?.data?.text ?? null
            };
        }
    }

    const help = async (text: string) => {
        try {
            let {data} = await axios.post(`${BACK}/chat`, {text}, {headers: {'Content-Type': 'application/json'}});

            return {
                done: true,
                text: data.text,
                role: data.role
            };
        } catch (fail: any) {
            return {
                done: false,
                fail: fail
            };
        }
    }

    const ring = async (type: number) => {
        try {
            let {data} = await axios.post(`${BACK}/bell`, {type}, {headers: {'Content-Type': 'application/json'}});

            return {
                done: true,
                list: data
            };
        } catch (fail: any) {
            return {
                done: false,
                fail: fail
            };
        }
    }

    return (
        <SessionContext.Provider value={{
            session,
            ping,
            sign,
            exit,
            help,
            ring
        }}>
            {properties.children}
        </SessionContext.Provider>
    )
}

export const SessionContext = createContext<ContextProperties | null>(null)