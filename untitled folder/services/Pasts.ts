import axios from "axios";

import {BACK} from "./../environment";

import type {Null} from "./../types/Null";

import type {Hash} from "./../types/Hash";

export default {
    load (type: 1 | 2 | 3, take: Null<number>, page: Null<number>, find: Null<string>, pipe: Null<string>, load: Null<string>, sort: Null<Hash<boolean>>, back: (done: boolean, data: any) => void) {
        axios.post(`${BACK}/pasts/${type}/load?take=${(take ?? 0)}&page=${(page ?? 0)}&find=${(find ?? '')}`, {
            sort: Object.keys((sort ?? {})).map((name) => ({
                name: name,
                down: sort?.[name]
            }))
        }).then((done) => {
            back(true, done.data);
        }).catch((fail) => {
            back(false, {text: fail?.response?.data?.text ?? ((typeof fail?.response?.data == 'string') ? fail?.response?.data : 'Se presentó una excepción no esperada.')});
        });
    },
    find (type: 1 | 2 | 3, item: number | string, back: (done: boolean, data: any) => void) {
        axios.get(`${BACK}/pasts/${type}/load/${item}`).then((done) => {
            back(true, done.data);
        }).catch((fail) => {
            back(false, {text: fail?.response?.data?.text ?? ((typeof fail?.response?.data == 'string') ? fail?.response?.data : 'Se presentó una excepción no esperada.')});
        });
    },
    post (type: 1 | 2 | 3, data: any, back: (done: boolean, data: any) => void) {
        axios.post(`${BACK}/pasts/${type}/post`, data)
             .then((done) => {
            back(true, done.data);
        }).catch((fail) => {
            back(false, {form: fail?.response?.data?.form ?? {}, text: fail?.response?.data?.text ?? ((typeof fail?.response?.data == 'string') ? fail?.response?.data : 'Se presentó una excepción no esperada.')});
        });
    },
    dump (type: 1 | 2 | 3, item: number | string, back: (done: boolean, data: any) => void) {
        axios.post(`${BACK}/pasts/${type}/dump/${item}`)
             .then((done) => {
            back(true, done.data);
        }).catch((fail) => {
            back(false, {form: fail?.response?.data?.form ?? {}, text: fail?.response?.data?.text ?? ((typeof fail?.response?.data == 'string') ? fail?.response?.data : 'Se presentó una excepción no esperada.')});
        });
    },
    save (type: 1 | 2 | 3, item: number | string, data: any, back: (done: boolean, data: any) => void) {
        axios.post(`${BACK}/pasts/${type}/save/${item}`, data)
             .then(function (done) {
            back(true, done.data.data);
        }).catch((fail: any) => {
            back(false, {form: fail?.response?.data?.form ?? {}, text: fail?.response?.data?.text ?? ((typeof fail?.response?.data == 'string') ? fail?.response?.data : 'Se presentó una excepción no esperada.')});
        });
    },
    drop (type: 1 | 2 | 3, item: number | string, back: (done: boolean, data: any) => void) {
        axios.delete(`${BACK}/pasts/${type}/drop/${item}`)
             .then(function (done) {
            back(true, done.data);
        }).catch((fail: any) => {
            back(false, {text: fail?.response?.data?.text ?? ((typeof fail?.response?.data == 'string') ? fail?.response?.data : 'Se presentó una excepción no esperada.')});
        });
    }
};