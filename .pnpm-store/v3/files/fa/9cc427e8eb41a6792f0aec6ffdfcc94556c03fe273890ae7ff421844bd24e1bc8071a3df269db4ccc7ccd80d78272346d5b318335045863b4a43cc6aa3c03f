type Props = {
    value: string;
    numInputs?: number;
    separator?: string;
    inputClasses?: string | string[];
    conditionalClass?: string[];
    inputType?: "number" | "tel" | "letter-numeric" | "password";
    inputmode?: "none" | "text" | "tel" | "url" | "email" | "numeric" | "decimal" | "search";
    shouldAutoFocus?: boolean;
    placeholder?: string[];
    isDisabled?: boolean;
    shouldFocusOrder?: boolean;
};
declare const _default: import('vue').DefineComponent<__VLS_WithDefaults<__VLS_TypePropsToRuntimeProps<Props>, {
    value: string;
    numInputs: number;
    separator: string;
    inputClasses: string;
    inputmode: string;
    shouldAutoFocus: boolean;
}>, {
    clearInput: () => void;
    fillInput: (value: string) => void;
}, unknown, {}, {}, import('vue').ComponentOptionsMixin, import('vue').ComponentOptionsMixin, {
    "update:value": (value: string) => void;
    "on-change": (value: string) => void;
    "on-complete": (value: string) => void;
}, string, import('vue').PublicProps, Readonly<import('vue').ExtractPropTypes<__VLS_WithDefaults<__VLS_TypePropsToRuntimeProps<Props>, {
    value: string;
    numInputs: number;
    separator: string;
    inputClasses: string;
    inputmode: string;
    shouldAutoFocus: boolean;
}>>> & {
    "onOn-change"?: ((value: string) => any) | undefined;
    "onUpdate:value"?: ((value: string) => any) | undefined;
    "onOn-complete"?: ((value: string) => any) | undefined;
}, {
    inputmode: "tel" | "none" | "text" | "url" | "email" | "numeric" | "decimal" | "search";
    value: string;
    separator: string;
    inputClasses: string | string[];
    shouldAutoFocus: boolean;
    numInputs: number;
}, {}>;
export default _default;
type __VLS_NonUndefinedable<T> = T extends undefined ? never : T;
type __VLS_TypePropsToRuntimeProps<T> = {
    [K in keyof T]-?: {} extends Pick<T, K> ? {
        type: import('vue').PropType<__VLS_NonUndefinedable<T[K]>>;
    } : {
        type: import('vue').PropType<T[K]>;
        required: true;
    };
};
type __VLS_WithDefaults<P, D> = {
    [K in keyof Pick<P, keyof P>]: K extends keyof D ? __VLS_Prettify<P[K] & {
        default: D[K];
    }> : P[K];
};
type __VLS_Prettify<T> = {
    [K in keyof T]: T[K];
} & {};
