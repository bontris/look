import {
    ReactNode
} from "react";

import {
    alpha,
    Stack,
    Avatar,
    SvgIcon,
    Typography
} from "@mui/material";

export const Fail = ({text, children}: {
    text?: string,
    children?: ReactNode
}) => {
    return (
        <Stack alignItems="center">
            <Avatar sx={(theme) => (
                {
                    mb: 2,
                    width: 96,
                    height: 96,
                    stroke: theme.palette.divider,
                    background: alpha(theme.palette.secondary.light, 0.16),
                }
            )}>
                <SvgIcon sx={{width: 64, height: 64}}>
                    <g
                        strokeLinejoin="round"
                        strokeLinecap="round"
                        strokeWidth="2"
                        fill="none">
                        <path d="M12 9v4m-1.637-9.409L2.257 17.125a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636-2.87L13.637 3.59a1.914 1.914 0 0 0-3.274 0zM12 16h.01"/>
                    </g>
                </SvgIcon>
            </Avatar>
            <Typography
                color="text.primary"
                variant="h6"
                gutterBottom>
                {(text ?? 'Error')}
            </Typography>
            <Typography
                color="text.secondary"
                gutterBottom>
                {(children ?? 'Lo sentimos, algo salió mal.')}
            </Typography>
        </Stack>
    );
}