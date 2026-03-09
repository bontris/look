import {ReactNode, useState, createContext} from "react";

import axios from "axios";

import {BACK} from "./../environment";

type Type = 'Roles' |
            'Users' |
            'Calls' |
            'Groups' |
            'Modules' |
            'Results' |
            'Searches' |
            'Sessions' |
            'Permissions'

type Resource = {
    type: Type,
    take?: number
    page?: number
    find?: string
    with?: string
    sort: {
        [name: string]: any
    }
    time?: number
}

export const ResourceProvider = ({resource, children}: {
    resource: Resource,
    children: ReactNode
}) => {
    const useList = async (back?: {[name: string]: any}) => {
        try {
            let {data} = await axios.get(`${BACK}/${resource.type}/list`);

            return {
                done: true,
                data: data
            };
        } catch (fail: any) {
            return {
                done: false,
                form: fail?.response?.data?.Form ?? {},
                text: fail?.response?.data?.detail ?? fail?.response?.data?.title ?? fail?.response?.data?.Text ?? null
            };
        }
    }

    const useFind = async (item: number | string, back?: {[name: string]: any}) => {
        try {
            let {data} = await axios.get(`${BACK}/${resource.type}/find/${item}`);

            return {
                done: true,
                data: data
            };
        } catch (fail: any) {
            return {
                done: false,
                form: fail?.response?.data?.Form ?? {},
                text: fail?.response?.data?.detail ?? fail?.response?.data?.title ?? fail?.response?.data?.Text ?? null
            };
        }
    }

    const useMake = async (post: any, back?: {[name: string]: any}) => {
        try {
            let form = new FormData();

            Object.keys(post).forEach((name) => {
                form.append(name, data[name]);
            });

            let {data} = await axios.post(`${BACK}/${resource.type}/make`, {'Content-Type': 'multipart/form-data'});

            return {
                done: true,
                data: data
            };
        } catch (fail: any) {
            return {
                done: false,
                form: fail?.response?.data?.Form ?? {},
                text: fail?.response?.data?.detail ?? fail?.response?.data?.title ?? fail?.response?.data?.Text ?? null
            };
        }
    }

    return (
        <ResourceContext.Provider value={{
            resource,
            useList,
            useFind,
            useMake
        }}>
            {children}
        </ResourceContext.Provider>
    )
}

export const ResourceContext = createContext<{
    resource: Resource;
    useList: (back?: {[name: string]: any}) => Promise<any>;
    useFind: (item: number | string, back?: {[name: string]: any}) => Promise<any>;
    useMake: (form: any, back?: {[name: string]: any}) => Promise<any>
    useSave?: (form: any, item: number | string) => Promise<any>
    useDrop?: (item: number | string) => Promise<any>
} | null>(null)