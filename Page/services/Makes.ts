import axios from "axios";

import {BACK} from "./../environment";

import type {Null} from "./../types/Null";

import type {Hash} from "./../types/Hash";

export default {
    load (take: Null<number>, page: Null<number>, find: Null<string>, pipe: Null<string>, load: Null<string>, sort: Null<Hash<boolean>>, back: (done: boolean, data: any) => void, item?: number | string, type?: 'note') {
        axios.post(`${BACK}/makes/load?take=${(take ?? '')}&page=${(page ?? '')}&find=${(find ?? '')}`, {
            sort: Object.keys((sort ?? {})).map((name) => ({
                name: name,
                down: sort?.[name]
            })),
            pipe: pipe
        }).then((done) => {
            back(true, done.data);
        }).catch((fail) => {
            back(false, {text: fail?.response?.data?.text ?? ((typeof fail?.response?.data == 'string') ? fail?.response?.data : 'Se presentó una excepción no esperada.')});
        });
    },
    find (item: number | string, pull: string, back: (done: boolean, data: any) => void,) {
        axios.get(`${BACK}/makes/find/${item}${pull ? `?with=${pull}` : ''}`).then((done) => {
            back(true, done.data);
        }).catch((fail) => {
            back(false, {text: fail?.response?.data?.text ?? ((typeof fail?.response?.data == 'string') ? fail?.response?.data : 'Se presentó una excepción no esperada.')});
        });
    },
    seek (item: Null<number | string>, load: Null<number | string>, back: (done: boolean, data: any) => void) {
        axios.get(`${BACK}/makes/seek${((item && `/${item}`) ?? '')}${((load && `?load=${load}`) ?? '')}`).then((done) => {
            back(true, done.data);
        }).catch((fail) => {
            back(false, {text: fail?.response?.data?.text ?? ((typeof fail?.response?.data == 'string') ? fail?.response?.data : 'Se presentó una excepción no esperada.')});
        });
    },
    date (back: (done: boolean, data: any) => void) {
        axios.get(`${BACK}/makes/date`).then((done) => {
            back(true, done.data);
        }).catch((fail) => {
            back(false, {text: fail?.response?.data?.text ?? ((typeof fail?.response?.data == 'string') ? fail?.response?.data : 'Se presentó una excepción no esperada.')});
        });
    },
    make (data: any, back: (done: boolean, data: any) => void, item?: number | string, type?: 'note') {
        axios.post(`${BACK}/makes/make${((item && `/${item}`) ?? '')}${((type && `/${type}`) ?? '')}`, data, {
            headers: {
              'Content-Type': 'multipart/form-data'
            }
        }).then((done) => {
            back(true, done.data);
        }).catch((fail) => {
            back(false, {form: fail?.response?.data?.form ?? {}, text: fail?.response?.data?.text ?? ((typeof fail?.response?.data == 'string') ? fail?.response?.data : 'Se presentó una excepción no esperada.')});
        });
    },
    save (item: number | string, data: any, back: (done: boolean, data: any) => void, type?: 'note', part?: number | string) {console.log('send', data)
        axios.post(`${BACK}/makes/save/${item}${((type && `/${type}`) ?? '')}${((part && `/${part}`) ?? '')}`, data, {
            headers: {
              'Content-Type': 'multipart/form-data'
            }
        }).then(function (done) {
            back(true, done.data.data);
        }).catch((fail: any) => {
            back(false, {form: fail?.response?.data?.form ?? {}, text: fail?.response?.data?.text ?? ((typeof fail?.response?.data == 'string') ? fail?.response?.data : 'Se presentó una excepción no esperada.')});
        });
    },
    risk (item: number | string, back: (done: boolean, data: any) => void, type?: 'note', part?: number | string) {
        axios.post(`${BACK}/makes/risk/${item}`).then(function (done) {
            back(true, done.data);
        }).catch((fail: any) => {
            back(false, {form: fail?.response?.data?.form ?? {}, text: fail?.response?.data?.text ?? ((typeof fail?.response?.data == 'string') ? fail?.response?.data : 'Se presentó una excepción no esperada.')});
        });
    },
    bulk (data: any, back: (done: boolean, data: any) => void) {
        axios.post(`${BACK}/makes/bulk`, data, {
            timeout: 900000,
            headers: {
              'Content-Type': 'multipart/form-data'
            }
        }).then((done) => {
            back(true, done.data);
        }).catch((fail) => {
            back(false, {list: fail?.response?.data?.list ?? {}, text: fail?.response?.data?.text ?? ((typeof fail?.response?.data == 'string') ? fail?.response?.data : 'Se presentó una excepción no esperada.')});
        });
    },
    drop (item: number | string, back: (done: boolean, data: any) => void, type?: 'note', part?: number | string) {
        axios.post(`${BACK}/makes/drop/${item}${((type && `/${type}`) ?? '')}${((part && `/${part}`) ?? '')}`)
             .then(function (done) {
            back(true, done.data);
        }).catch((fail: any) => {
            back(false, {text: fail?.response?.data?.text ?? ((typeof fail?.response?.data == 'string') ? fail?.response?.data : 'Se presentó una excepción no esperada.')});
        });
    }
};