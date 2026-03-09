import axios from 'axios';

import {BACK} from "./../environment";

import type {Null} from "./../types/Null";

import type {Hash} from "./../types/Hash";

export default {
    load (take: Null<number>, page: Null<number>, find: Null<string>, pipe: Null<string>, load: Null<string>, sort: Null<Hash<boolean>>, back: (done: boolean, data: any) => void) {
        axios.post(`${BACK}/admin/plates/search`, {
            paginationParams: {
                page: ((page ?? 1) - 1),
                pageSize: take ?? 0,
                sortModel: Object.keys((sort ?? {})).map((name) => ({
                    field: name,
                    sort: sort?.[name] ? 'DESC' : 'ASC'
                }))
            },
            selectedCameraIds: [
                27,
                29,
                37,
                40,
                41,
                42,
                44,
                47,
                50,
                51,
                52,
                56,
                57,
                58,
                80,
                81,
                82,
                85,
                116,
                122,
                134,
                226,
                232,
                233,
                234,
                236
            ],
            selectedVehicleTypes: [
                "Car",
                "Moto",
                "Truck",
                "Bus"
            ],
            selectedViolationTypes: [
                "Speed",
                "Helmet",
                "RedLight",
                "BlackList",
                "IllegalTurn",
                "Seatbelt"
            ],
            startDate: '2024-06-22 06:00:00',
            endDate: '2024-06-24 12:00:00',
            startSpeed: 0,
            endSpeed: 0
        }).then((done) => {
            back(true, {
                Page: page ?? 1,
                Data: done.data.data,
                Size: done.data.total_count
            });
        }).catch((fail) => {
            back(false, {text: fail?.response?.data?.detail ?? fail?.response?.data?.title ?? fail?.response?.data?.Text ?? ((typeof fail?.response?.data == 'string') ? fail?.response?.data : 'Unexpected exception.')});
        });
    },
    async find (item: number | string, pull: string, back: (done: boolean, data: any) => void) {
        try {
            const done = await axios.get(`${BACK}/admin/users/get/${item}${pull ? `?with=${pull}` : ''}`);

            if (back) {
                back(true, done.data.data);
            }

            return done.data;
        } catch (fail: any) {
            if (back) {
                back(false, {text: fail?.response?.data?.detail ?? fail?.response?.data?.title ?? fail?.response?.data?.Text ?? (typeof fail?.response?.data == 'string' ? fail?.response?.data : 'Se presentó un problema no esperado.')});
            }
        }
    },
    make (data: any, back: (done: boolean, data: any) => void) {
        axios.post(`${BACK}/Users/make`, data)
             .then((done) => {
            back(true, done.data);
        }).catch((fail) => {
            back(false, {form: fail?.response?.data?.Form ?? {}, text: fail?.response?.data?.detail ?? fail?.response?.data?.title ?? fail?.response?.data?.Text ?? (typeof fail?.response?.data == 'string' ? fail?.response?.data : 'Se presentó un problema no esperado.')});
        });
    },
    save (item: number | string, data: any, back: (done: boolean, data: any) => void) {
        axios.put(`${BACK}/admin/users/${item}`, data)
             .then(function (done) {
            back(true, done.data.data);
        }).catch((fail: any) => {
            back(false, {form: fail?.response?.data?.Form ?? {}, text: fail?.response?.data?.detail ?? fail?.response?.data?.title ?? fail?.response?.data?.Text ?? (typeof fail?.response?.data == 'string' ? fail?.response?.data : 'Se presentó un problema no esperado.')});
        });
    },
    drop (item: number | string, back: (done: boolean, data: any) => void) {
        axios.delete(`${BACK}/Users/drop/${item}`)
             .then(function (done) {
            back(true, done.data);
        }).catch((fail: any) => {
            back(false, {text: fail?.response?.data?.detail ?? fail?.response?.data?.title ?? fail?.response?.data?.Text ?? (typeof fail?.response?.data == 'string' ? fail?.response?.data : 'Se presentó un problema no esperado.')});
        });
    }
};