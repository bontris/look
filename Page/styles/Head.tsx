import type { Theme } from "@mui/material/styles"

import styled from "@emotion/styled"

import type { CSSObject } from "@emotion/styled"

type Properties = {
    theme: Theme
    styles?: CSSObject
}

export const StyledHead = styled.header<Properties>`
    min-block-size: var(--header-height);
`