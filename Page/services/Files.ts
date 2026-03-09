import axios from "axios";

import {BACK} from "./../environment";

import type {Null} from "./../types/Null";

import type {Hash} from "./../types/Hash";

export default {
    load (item: Null<number | string>, page: Null<number>, find: Null<string>, pipe: Null<string>, load: Null<string>, sort: Null<Hash<boolean>>, back: (done: boolean, data: any) => void) {
        axios.post(`${BACK}/files/load${(item ? `/${item}` : '')}`, {
            sort: Object.keys((sort ?? {})).map((name) => ({
                name: name,
                down: sort?.[name]
            }))
        }).then((done) => {
            back(true, done.data);
        }).catch((fail) => {
            back(false, {text: fail?.response?.data?.text ?? ((typeof fail?.response?.data == 'string') ? fail?.response?.data : 'Unexpected exception.')});
        });
    },
    open (item: number | string, back: (done: boolean, data: any) => void) {
        axios.get(`${BACK}/files/open/${item}`).then((done) => {
            back(true, done.data);
        }).catch((fail) => {
            back(false, {text: fail?.response?.data?.text ?? ((typeof fail?.response?.data == 'string') ? fail?.response?.data : 'Unexpected exception.')});
        });
    },
    find (item: number | string, back: (done: boolean, data: any) => void) {
        axios.get(`${BACK}/files/find/${item}`).then((done) => {
            back(true, done.data);
        }).catch((fail) => {
            back(false, {text: fail?.response?.data?.text ?? ((typeof fail?.response?.data == 'string') ? fail?.response?.data : 'Unexpected exception.')});
        });
    },
    save (item: number | string, back: (done: boolean, data: any) => void) {
        axios.get(`${BACK}/files/save/${item}`, {responseType: 'blob'}).then((done) => {
            back(true, done.data);
        }).catch((fail) => {
            back(false, {form: fail?.response?.data?.form ?? {}, text: fail?.response?.data?.text ?? ((typeof fail?.response?.data == 'string') ? fail?.response?.data : 'Se presentó una excepción no esperada.')});
        });
    },
    drop (item: number | string, back: (done: boolean, data: any) => void) {
        axios.delete(`${BACK}/loads/drop/${item}`)
             .then(function (done) {
            back(true, done.data);
        }).catch((fail: any) => {
            back(false, {text: fail?.response?.data?.text ?? ((typeof fail?.response?.data == 'string') ? fail?.response?.data : 'Unexpected exception.')});
        });
    }
};