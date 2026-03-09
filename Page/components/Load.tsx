import {
    Box,
    CircularProgress
} from "@mui/material";

export const Load = ({size}: {
    size?: number
}) => {
    return (
        <Box
            justifyContent="center"
            alignItems="center"
            display="flex">
            <CircularProgress size={(size ?? 48)} />
        </Box>
    );
}