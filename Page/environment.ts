export const HOST = 'http://localhost:8080';

export const BACK = 'http://shop.test';

//export const HOST = 'https://app.tallera.co';

//export const BACK = 'https://shop.bontris.com';

export const NAME = 'Taller A';

export const TINT = '#FF6B00';

export const WIDE = true;

export const LOCK = 2000;

export const SIDE = 320;

export const HEAD = 54;

export const ROOT = 1;

export const TEAM = 2;

export const SHOP = 4;

export const TEXT = [
    {
        code: 'en',
        name: 'English'
    },
    {
        code: 'es',
        name: 'Español'
    },
    {
        code: 'fr',
        name: 'Français'
    }
]

export const MODE = [
    {
        code: 'light',
        name: 'Claro'
    },
    {
        code: 'dark',
        name: 'Oscuro'
    },
    {
        code: 'system',
        name: 'Sistema'
    }
]

export const IDLE = [
    '#9E9E9E',
    '#558B2F',
    '#FFB300',
    '#F44336'
]

export const MENU = [
    {
        name: 'Home',
        path: '/',
        text: 'Inicio',
        icon: ['m19 8.71l-5.333-4.148a2.666 2.666 0 0 0-3.274 0L5.059 8.71a2.665 2.665 0 0 0-1.029 2.105v7.2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-7.2c0-.823-.38-1.6-1.03-2.105', 'M16 15c-2.21 1.333-5.792 1.333-8 0']
    },
    {
        type: [1, 4],
        name: 'Chats',
        path: '/abogado',
        text: 'Abogado virtual',
        icon: ['M6 5h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2', 'M9 16q1.5 1 3 1c1.5 0 2-.333 3-1M9 7L8 3m7 4l1-4m-7 9v-1m6 1v-1']
    },
    {
        type: [1, 3],
        name: 'Makes',
        path: '/vigilancia',
        text: 'Vigilancia de marcas',
        icon: ['M3 12a9 9 0 1 0 18 0a9 9 0 1 0-18 0', 'M10 15V9h2a2 2 0 1 1 0 4h-2m4 2l-2-2']
    },
    {
        type: 1,
        name: 'Cards',
        path: '/anuncios',
        text: 'Anuncios',
        icon: ['M15 6h.01M3 6a3 3 0 0 1 3-3h12a3 3 0 0 1 3 3v8a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3z', 'm3 13l4-4a3 5 0 0 1 3 0l4 4', 'm13 12l2-2a3 5 0 0 1 3 0l3 3M8 21h.01M12 21h.01M16 21h.01']
    },
    {
        type: 1,
        name: 'Rings',
        path: '/notificaciones',
        text: 'Notificaciones',
        icon: ['M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3H4a4 4 0 0 0 2-3v-3a7 7 0 0 1 4-6M9 17v1a3 3 0 0 0 6 0v-1']
    },
    {
        type: 1,
        name: 'Terms',
        path: '/gacetas',
        text: 'Gacetas',
        icon: ['M16 6h3a1 1 0 0 1 1 1v11a2 2 0 0 1-4 0V5a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v12a3 3 0 0 0 3 3h11M8 8h4m-4 4h4m-4 4h4']
    },
    {
        type: [1, 2, 3, 4],
        name: 'Tasks',
        path: '/servicios',
        text: 'Tus servicios',
        icon: ['M12.003 21c-.732.001-1.465-.438-1.678-1.317a1.724 1.724 0 0 0-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 0 0-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 0 0 1.066-2.573c-.94-1.543.826-3.31 2.37-2.37c1 .608 2.296.07 2.572-1.065c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c.886.215 1.325.957 1.318 1.694', 'M9 12a3 3 0 1 0 6 0a3 3 0 0 0-6 0m8.001 7a2 2 0 1 0 4 0a2 2 0 1 0-4 0m2-3.5V17m0 4v1.5m3.031-5.25l-1.299.75m-3.463 2l-1.3.75m0-3.5l1.3.75m3.463 2l1.3.75']
    },
    {
        type: [1, 2, 3, 4],
        name: 'Loads',
        path: '/informes',
        text: 'Tus informes',
        icon: ['M8 5H6a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h5.697M18 14v4h4m-4-7V7a2 2 0 0 0-2-2h-2', 'M8 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v0a2 2 0 0 1-2 2h-2a2 2 0 0 1-2-2m6 13a4 4 0 1 0 8 0a4 4 0 1 0-8 0m-6-7h4m-4 4h3']
    },
    {
        type: 4,
        name: 'Files',
        path: '/documentos',
        text: 'Dataroom legal',
        icon: ['M5 4h4l3 3h7a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2']
    },
    {
        type: [1, 2, 3, 4, 5],
        name: 'Tests',
        path: '/diagnosticos',
        text: 'Diagnóstico',
        icon: ['M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2', 'M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v0a2 2 0 0 1-2 2h-2a2 2 0 0 1-2-2m0 12v-5m3 5v-1m3 1v-3']
    },
    {
        open: false,
        text: 'Firma',
        icon: ['M12 3a12 12 0 0 0 8.5 3A12 12 0 0 1 12 21A12 12 0 0 1 3.5 6A12 12 0 0 0 12 3', 'M11 11a1 1 0 1 0 2 0a1 1 0 1 0-2 0m1 1v2.5'],
        list: [
            {
                type: 4,
                name: 'Documento',
                path: '/firmas/documentos/make',
                text: 'Sistema de firma de documentos',
                icon: ['M3 19q5-3 5-6c0-3-1-3-2-3s-2.032 1.085-2 3c.034 2.048 1.658 2.877 2.5 4C8 19 9 19.5 10 18q1-1.5 1.5-2.5q1.5 3.5 4 3.5H18m2-2V5c0-1.121-.879-2-2-2s-2 .879-2 2v12l2 2zM16 7h4']
            },
            {
                type: [1, 4],
                name: 'Documento',
                path: '/firmas/documentos',
                text: 'Listado de documentos firmados',
                icon: ['M3 17q5-5 5-8c0-3-1-3-2-3S3.968 7.085 4 9c.034 2.048 1.658 4.877 2.5 6C8 17 9 17.5 10 16l2-3q.5 4 3 4c.53 0 2.639-2 3-2q.776 0 3 2']
            }
        ]
    },
    {
        open: false,
        text: 'Validación',
        icon: ['M12 3a12 12 0 0 0 8.5 3A12 12 0 0 1 12 21A12 12 0 0 1 3.5 6A12 12 0 0 0 12 3', 'M11 11a1 1 0 1 0 2 0a1 1 0 1 0-2 0m1 1v2.5'],
        list: [
            {
                type: [1, 4],
                name: 'Antecedentes',
                path: '/validaciones/antecedentes',
                text: 'Antecedentes judiciales',
                icon: ['M8 5H6a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h5.697M18 12V7a2 2 0 0 0-2-2h-2', 'M8 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v0a2 2 0 0 1-2 2h-2a2 2 0 0 1-2-2m0 6h4m-4 4h3m3 2.5a2.5 2.5 0 1 0 5 0a2.5 2.5 0 1 0-5 0m4.5 2L21 22'],
                help: 'En este servicio puedes validar los antecedentes judiciales en Colombia de las personas naturales que desees consultar.'
            }
        ]
    },
    {
        open: false,
        text: 'Administración',
        icon: ['M12 3a12 12 0 0 0 8.5 3A12 12 0 0 1 12 21A12 12 0 0 1 3.5 6A12 12 0 0 0 12 3', 'M11 11a1 1 0 1 0 2 0a1 1 0 1 0-2 0m1 1v2.5'],
        list: [
            {
                type: 1,
                name: 'Sales',
                path: '/ventas',
                text: 'Ventas',
                icon: ['M3 8v4.172a2 2 0 0 0 .586 1.414l5.71 5.71a2.41 2.41 0 0 0 3.408 0l3.592-3.592a2.41 2.41 0 0 0 0-3.408l-5.71-5.71A2 2 0 0 0 9.172 6H5a2 2 0 0 0-2 2', 'm18 19l1.592-1.592a4.82 4.82 0 0 0 0-6.816L15 6m-8 4h-.01']
            },
            {
                type: 1,
                name: 'Kinds',
                path: '/clases',
                text: 'Clases',
                icon: ['M6.5 7.5a1 1 0 1 0 2 0a1 1 0 1 0-2 0', 'M3 6v5.172a2 2 0 0 0 .586 1.414l7.71 7.71a2.41 2.41 0 0 0 3.408 0l5.592-5.592a2.41 2.41 0 0 0 0-3.408l-7.71-7.71A2 2 0 0 0 11.172 3H6a3 3 0 0 0-3 3']
            },
            {
                type: 1,
                name: 'Hands',
                path: '/equipo',
                text: 'Equipo',
                icon: ['M8 7a4 4 0 1 0 8 0a4 4 0 0 0-8 0M6 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2']
            },
            {
                type: 1,
                name: 'Lead',
                path: '/clientes',
                text: 'Clientes',
                icon: ['M8 7a4 4 0 1 0 8 0a4 4 0 0 0-8 0M6 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2']
            },
            {
                type: 1,
                name: 'Firms',
                path: '/empresas',
                text: 'Empresas',
                icon: ['M3 21h18M9 8h1m-1 4h1m-1 4h1m4-8h1m-1 4h1m-1 4h1M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16']
            },
            {
                type: 1,
                name: 'Banks',
                path: '/tokens',
                text: 'Tokens',
                icon: ['M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0c1.172-.879 1.172-2.303 0-3.182c-1.171-.879-3.07-.879-4.242 0L12 14.5']
            }
        ]
    }
]