import { SubjectType, Generics, AnyAbility, Ability, Abilities, IfString, AbilityTuple } from '@casl/ability';
type AbilityCanProps<T extends Abilities, Else = IfString<T, {
    do: T;
} | {
    I: T;
}>> = T extends AbilityTuple ? {
    do: T[0];
    on: T[1];
    field?: string;
} | {
    I: T[0];
    a: Extract<T[1], SubjectType>;
    field?: string;
} | {
    I: T[0];
    an: Extract<T[1], SubjectType>;
    field?: string;
} | {
    I: T[0];
    this: Exclude<T[1], SubjectType>;
    field?: string;
} : Else;
export type CanProps<T extends AnyAbility> = AbilityCanProps<Generics<T>['abilities']> & {
    not?: boolean;
    passThrough?: boolean;
};
export declare const Can: import("vue").DefineComponent<CanProps<Ability<AbilityTuple, import("@casl/ability").MongoQuery>>, {}, {}, {}, {}, import("vue").ComponentOptionsMixin, import("vue").ComponentOptionsMixin, {}, string, import("vue").PublicProps, Readonly<{
    do: string;
    on: import("@casl/ability").Subject;
    field?: string | undefined;
} & {
    not?: boolean | undefined;
    passThrough?: boolean | undefined;
}> | Readonly<{
    I: string;
    a: SubjectType;
    field?: string | undefined;
} & {
    not?: boolean | undefined;
    passThrough?: boolean | undefined;
}> | Readonly<{
    I: string;
    an: SubjectType;
    field?: string | undefined;
} & {
    not?: boolean | undefined;
    passThrough?: boolean | undefined;
}> | Readonly<{
    I: string;
    this: {
        [x: string]: any;
        [x: number]: any;
        [x: symbol]: any;
    };
    field?: string | undefined;
} & {
    not?: boolean | undefined;
    passThrough?: boolean | undefined;
}>, {} | {} | {} | {}, {}>;
export {};
