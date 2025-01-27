const MAIN_STORAGE_KEY = 'store';

/**
 * Generates a local storage key.
 * @param {string} key - The key to be used for storage.
 * @returns {string} - The full local storage key.
 */
const generateStorageKey = (key) => `${MAIN_STORAGE_KEY}_${key}`;

/**
 * Safely parses a JSON string and returns the parsed object or an empty object.
 * @param {string} input - The JSON string to be parsed.
 * @returns {object} - The parsed object or an empty object if parsing fails.
 */
const JSONTryParse = (input) => {
    try {
        return input ? JSON.parse(input) : {};
    } catch (e) {
        return {};
    }
};

/**
 * Saves an object to local storage under a specified key.
 * @param {string} key - The key under which the object will be stored.
 * @param {object} obj - The object to be stored.
 */
export const saveStorage = (key, obj) => {
    const objStr = JSON.stringify(obj);
    localStorage.setItem(generateStorageKey(key), objStr);
};

/**
 * Retrieves an object from local storage by key.
 * @param {string} key - The key to retrieve the object.
 * @returns {object|null} - The retrieved object or null if not found.
 */
export const getStorage = (key) => {
    const str = localStorage.getItem(generateStorageKey(key));
    return str ? JSONTryParse(str) : null;
};

/**
 * Removes an item from local storage by key.
 * @param {string} key - The key of the item to be removed.
 */
export const removeStorage = (key) => {
    localStorage.removeItem(generateStorageKey(key));
};
