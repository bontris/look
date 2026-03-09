import axios from "axios";

import {BACK} from "./../environment";

import type {Null} from "./../types/Null";

import type {Hash} from "./../types/Hash";

export default {
    load (item: Null<number | string>, page: Null<number>, find: Null<string>, pipe: Null<string>, load: Null<string>, sort: Null<Hash<boolean>>, back: (done: boolean, data: any) => void) {
        axios.post(`${BACK}/loads/load${(item ? `/${item}` : '')}?page=${(page ?? 0)}&find=${(find ?? '')}`, {
            sort: Object.keys((sort ?? {})).map((name) => ({
                name: name,
                down: sort?.[name]
            }))
        }).then((done) => {
            back(true, done.data);
        }).catch((fail) => {
            back(false, {text: fail?.response?.data?.text ?? 'Se presentó una excepción no esperada.'});
        });
    },
    find (item: number | string, back: (done: boolean, data: any) => void) {
        axios.get(`${BACK}/loads/find/${item}`).then((done) => {
            back(true, done.data);
        }).catch((fail) => {
            back(false, {text: fail?.response?.data?.text ?? 'Se presentó una excepción no esperada.'});
        });
    },
    dump (item: Null<number | string>, data: any, back: (done: boolean, data: any) => void) {
        axios.post(`${BACK}/loads/dump${(item ? `/${item}` : '')}`, data, {timeout: 900000})
             .then(function (done) {
            back(true, done.data);
        }).catch((fail: any) => {
            back(false, {list: fail?.response?.data?.list ?? {}, text: fail?.response?.data?.text ?? 'Se presentó una excepción no esperada.'});
        });
    },
    post (data: any, back: (done: boolean, data: any) => void) {
        axios.post(`${BACK}/tasks/post`, data, {
            headers: {
              'Content-Type': 'multipart/form-data'
            }
        }).then((done) => {
            back(true, done.data);
        }).catch((fail) => {
            back(false, {form: fail?.response?.data?.form ?? {}, text: fail?.response?.data?.text ?? 'Se presentó una excepción no esperada.'});
        });
    },
    drop (item: number | string, back: (done: boolean, data: any) => void) {
        axios.delete(`${BACK}/loads/drop/${item}`)
             .then(function (done) {
            back(true, done.data);
        }).catch((fail: any) => {
            back(false, {text: fail?.response?.data?.text ?? 'Se presentó una excepción no esperada.'});
        });
    }
};