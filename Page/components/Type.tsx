import {
    useState
} from "react";

import {
    Stack,
    Button,
    SvgIcon,
    InputBase
} from "@mui/material";

import type {Null} from "../types/Null";

export const Type = ({send, lock, text, children}: {
    send: (text: string, file: Null<File>) => void,
    text?: string,
    lock?: boolean,
    children?: string
}) => {
    const [form, setForm] = useState<{
        text: Null<string>,
        file: Null<File>
        wait: boolean,
        lock: boolean
    }>({
        wait: false,
        lock: false,
        text: text,
        file: null
    });

    return (
        <Stack
            autoComplete="off"
            component="form"
            direction="row"
            onSubmit={async (event) => {
                event.preventDefault();

                if (form.text?.trim()) {
                    send(form.text, form.file);

                    setForm((last) => ({
                        ...last,
                        text: null,
                        file: null
                    }));
                }
            }}
            gap={1}
            noValidate>
            <InputBase
                sx={{px: 1}}
                value={(form.text ?? '')}
                readOnly={lock}
                placeholder={(children ?? 'Escribe un mensaje...')}
                onChange={(event) => {
                    setForm((last) => ({
                        ...last,
                        text: event.target.value
                    }));
                }}
                fullWidth />
            <Button
                sx={{px: 1}}
                type="submit"
                color="primary"
                variant="contained"
                disabled={lock}>
                <SvgIcon sx={{width: 24, height: 24}}>
                    <g
                        strokeLinejoin="round"
                        strokeLinecap="round"
                        strokeWidth="1.5"
                        stroke="currentColor"
                        fill="none">
                        <path d="M12 5v14m4-10l-4-4M8 9l4-4" />
                    </g>
                </SvgIcon>
            </Button>
        </Stack>
    );
}