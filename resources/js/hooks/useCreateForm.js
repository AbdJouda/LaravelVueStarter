import {useField, useForm} from 'vee-validate';

export function useCreateForm(options) {

    const {initialValues, schema} = options;

    const form = useForm({
        initialValues,
        validationSchema: schema,
        validateOnMount: true,
    });

    const fields = Object.fromEntries(
        Object.entries(initialValues).map(([key, _value]) => [key, useField(key)])
    );

    const getErrorMessage = (field) => {
        return computed(() => {
            return fields[field].meta.touched && form.errors.value[field]
                ? form.errors.value[field]
                : '';
        }).value;
    };



    return {
        form,
        fields,
        getErrorMessage
    };
}
