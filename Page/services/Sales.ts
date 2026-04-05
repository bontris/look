import axios from "axios";

import {BACK} from "../environment";

import type {Null} from "../types/Null";

import type {Hash} from "../types/Hash";

export default {
    load (take: Null<number>, page: Null<number>, find: Null<string>, pipe: Null<string>, load: Null<string>, sort: Null<Hash<boolean>>, back: (done: boolean, data: any) => void) {
        axios.post(`${BACK}/sales/load?page=${page ?? 0}&find=${find ?? ''}&take=${take ?? 16}`, {
            pipe: pipe,
            load: load,
            sort: Object.keys((sort ?? {})).map((name) => ({
                name: name,
                down: sort?.[name]
            }))
        }).then((done) => {
            back(true, done.data);
        }).catch((fail) => {
            back(false, {text: fail?.response?.data?.detail ?? fail?.response?.data?.title ?? fail?.response?.data?.text ?? (typeof fail?.response?.data == 'string' ? fail?.response?.data : 'Se presentó un problema no esperado.')});
        });
    },
    find (item: number | string, pull: string, back: (done: boolean, data: any) => void) {
        axios.get(`${BACK}/sales/load/${item}${pull ? `?with=${pull}` : ''}`).then((done) => {
            back(true, done.data);
        }).catch((fail) => {
            back(false, {text: fail?.response?.data?.detail ?? fail?.response?.data?.title ?? fail?.response?.data?.text ?? (typeof fail?.response?.data == 'string' ? fail?.response?.data : 'Se presentó un problema no esperado.')});
        });
    },
    make (data: any, back: (done: boolean, data: any) => void) {
        axios.post(`${BACK}/sales/make`, data).then((done) => {
            back(true, done.data);
        }).catch((fail) => {
            back(false, {list: fail?.response?.data?.list ?? {}, text: fail?.response?.data?.detail ?? fail?.response?.data?.title ?? fail?.response?.data?.text ?? (typeof fail?.response?.data == 'string' ? fail?.response?.data : 'Se presentó un problema no esperado.')});
        });
    },
    save (item: number | string, data: any, back: (done: boolean, data: any) => void) {
        axios.post(`${BACK}/sales/save/${item}`, data).then(function (done) {
            back(true, done.data.data);
        }).catch((fail: any) => {
            back(false, {list: fail?.response?.data?.list ?? {}, text: fail?.response?.data?.detail ?? fail?.response?.data?.title ?? fail?.response?.data?.text ?? (typeof fail?.response?.data == 'string' ? fail?.response?.data : 'Se presentó un problema no esperado.')});
        });
    },
    drop (item: number | string, back: (done: boolean, data: any) => void) {
        axios.post(`${BACK}/sales/drop/${item}`)
             .then(function (done) {
            back(true, done.data);
        }).catch((fail: any) => {
            back(false, {text: fail?.response?.data?.detail ?? fail?.response?.data?.title ?? fail?.response?.data?.Text ?? (typeof fail?.response?.data == 'string' ? fail?.response?.data : 'Se presentó un problema no esperado.')});
        });
    }
};