import {
    ReactNode,
    Fragment,
    useRef,
    useState
} from "react";

import type {
    MouseEvent,
    ReactElement
} from "react";

import {
    Box,
    Fade,
    Paper,
    Popper,
    SvgIcon,
    MenuList,
    MenuItem,
    IconButton,
    ClickAwayListener
} from "@mui/material";

export const Menu = ({icon, pick, list}: {
    pick?: string | number,
    icon?: ReactNode,
    list: Array<{
        icon?: Array<string> | string,
        item: string | number,
        text: string
    }>
}) => {
    const [menu, setMenu] = useState<{open: boolean, node: HTMLElement | null}>({
        open: false,
        node: null
    });
    const hide = (event?: MouseEvent<HTMLLIElement> | (MouseEvent | TouchEvent), path?: string) => {
        setMenu((last) => ({...last, open: false}));
    }

    const show = (event: React.MouseEvent<HTMLElement>) => {
        setMenu((last) => ({...last, node: event.currentTarget, open: true}));
    }

    return (
        <Fragment>
            <IconButton onClick={show}>
                {(icon ?? (
                    <SvgIcon sx={{width: '24px', height: '24px'}}>
                        <path
                            strokeLinejoin="round"
                            strokeLinecap="round"
                            strokeWidth="2"
                            d="M11 12a1 1 0 1 0 2 0a1 1 0 1 0-2 0m0 7a1 1 0 1 0 2 0a1 1 0 1 0-2 0m0-14a1 1 0 1 0 2 0a1 1 0 1 0-2 0" />
                    </SvgIcon>
                ))}
            </IconButton>
            <Popper
                sx={{zIndex: (theme) => (theme.zIndex.drawer + 1), borderRadius: '6px'}}
                open={menu.open}
                anchorEl={menu.node}
                placement="bottom-end"
                onClick={hide}
                disablePortal
                transition>
                {({TransitionProps, placement}) => (
                    <Fade
                        {...TransitionProps}
                        style={{
                            transformOrigin: (placement == 'bottom-end') ? 'right top' : 'left top'
                        }}>
                        <Paper className="shadow">
                            <ClickAwayListener onClickAway={event => hide(event as MouseEvent | TouchEvent)}>
                                <MenuList>
                                    {list.map((menu) => (
                                        <MenuItem
                                            key={menu.item}
                                            selected={(menu.item == pick)}>
                                            {(menu.icon && (
                                                <SvgIcon sx={{width: '24px', height: '24px'}}>
                                                    <g
                                                        strokeLinejoin="round"
                                                        strokeLinecap="round"
                                                        strokeWidth="2"
                                                        fill="none">
                                                        {(menu.icon instanceof Array ? menu.icon.map((path) => (<path d={path} />)) : <path d={menu.icon} />)}
                                                    </g>
                                                </SvgIcon>
                                            ))}
                                            {menu.text}
                                        </MenuItem>
                                    ))}
                                </MenuList>
                            </ClickAwayListener>
                        </Paper>
                    </Fade>
                )}
            </Popper>
        </Fragment>
    );
}