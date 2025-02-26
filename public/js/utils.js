/** @param any input */
function isValidInt(input) {
    return !(isNaN(input) || Number(input) != parseInt(input))
}
/** @param any input */
function isValidFloat(input) {
    const num = parseFloat(input);
    return !isNaN(num);
}
/** @param {Element | null} inputElement */
function addInputWarning(inputElement) {
    if (inputElement) {
        inputElement.classList.add("input-warning");
    }
}
/** @param {Element | null} inputElement */
function removeInputWarning(inputElement) {
    if (inputElement) {
        inputElement.classList.remove("input-warning")
    }
}