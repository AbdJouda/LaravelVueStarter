import * as Yup from 'yup';

const REQ_MSG = 'This Field is required';
const phoneRegex = /^[0-9]{10,15}$/;

const usernameRule = Yup.string()
    .test('is-valid-email-or-phone', 'Must be a valid email or phone number', value => {
        return Yup.string().email().isValidSync(value) || phoneRegex.test(value);
    })

const passwordRule = Yup.string().min(8, ({ min }) => `The new password must be at least ${min} characters long.`);

const loginSchema = Yup.object().shape({
    username: usernameRule.required(REQ_MSG),
    password: Yup.string().required(REQ_MSG),
});

const forgotPasswordSchema = Yup.object().shape({
    username: usernameRule.required(REQ_MSG),
});

const resetPassSchema = Yup.object().shape({
    password: passwordRule.required(REQ_MSG),
    password_confirmation: passwordRule.required(REQ_MSG),
});

const updatePasswordSchema = Yup.object().shape({
    password: Yup.string().required(REQ_MSG),
    new_password: passwordRule.required(REQ_MSG),
    new_password_confirmation: passwordRule.required(REQ_MSG),
});

const updateProfileSchema = Yup.object().shape({
    name: Yup.string().required(REQ_MSG),
    email: Yup.string().email().required(REQ_MSG),
    phone: Yup.string().required(REQ_MSG),
    profile_photo: Yup.mixed()
        .nullable()
        .test('fileType', 'Image must be a JPEG or PNG image', (value) => {
            if (!value) return true;
            return value && value.type.startsWith('image/');
        }),
});

const userSchema = Yup.object().shape({
    name: Yup.string().required(REQ_MSG),
    email: Yup.string().email().required(REQ_MSG),
    phone: Yup.string().required(REQ_MSG),
    is_active: Yup.boolean().required(REQ_MSG),
    roles: Yup.array()
        .of(
            Yup.string()
                .matches(
                    /^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/,
                    'Each role must be a valid UUID'
                )
        )
        .notRequired(),
    permissions: Yup.array()
        .of(
            Yup.string()
                .matches(
                    /^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/,
                    'Each permission must be a valid UUID'
                )
        )
        .notRequired(),
});



const updateRoleSchema = Yup.object().shape({
    name: Yup.string().required(REQ_MSG),
    permissions: Yup.array()
        .of(
            Yup.string()
                .matches(
                    /^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/,
                    'Each permission must be a valid UUID'
                )
        )
        .notRequired(),
});

const updateSettingsSchema = Yup.object().shape({
    name: Yup.string().required(REQ_MSG),
    logo: Yup.mixed()
        .nullable()
        .test('fileType', 'Image must be a JPEG or PNG image', (value) => {
            if (!value) return true;
            return value && value.type.startsWith('image/');
        }),
    favicon: Yup.mixed()
        .nullable()
        .test('fileType', 'Image must be a JPEG or PNG image', (value) => {
            if (!value) return true;
            return value && value.type.startsWith('image/');
        }),
});


const validationSchemas = {
    loginSchema,
    forgotPasswordSchema,
    resetPassSchema,
    updateProfileSchema,
    updatePasswordSchema,
    userSchema,
    updateRoleSchema,
    updateSettingsSchema
};

export default validationSchemas;
