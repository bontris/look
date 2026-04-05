import axios from "axios";

import {BACK} from "./../environment";

import type {Null} from "./../types/Null";

import type {Hash} from "./../types/Hash";

export default {
    load (take: Null<number>, page: Null<number>, find: Null<string>, pipe: Null<string>, load: Null<string>, sort: Null<Hash<boolean>>, back: (done: boolean, data: any) => void) {
        axios.post(`${BACK}/firms/load?take=${(take ?? 0)}&page=${(page ?? 0)}&find=${(find ?? '')}`, {
            sort: Object.keys((sort ?? {})).map((name) => ({
                name: name,
                down: sort?.[name]
            }))
        }).then((done) => {
            back(true, done.data);
        }).catch((fail) => {
            back(false, {text: fail?.response?.data?.detail ?? fail?.response?.data?.title ?? fail?.response?.data?.Text ?? ((typeof fail?.response?.data == 'string') ? fail?.response?.data : 'Unexpected exception.')});
        });
    },
    bank (item: number | string, bank: number, back: (done: boolean, data: any) => void) {
        axios.post(`${BACK}/firms/bank/${item}`, { bank })
             .then((done) => {
            back(true, done.data);
        }).catch((fail: any) => {
            back(false, {text: fail?.response?.data?.text ?? 'Se presentó un problema no esperado.'});
        });
    }
};
