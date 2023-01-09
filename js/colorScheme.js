const colorScheme = {
    LIGHT: 'light',
    DARK: 'dark',
}

const colors = {
    '--colors-softblack': getComputedStyle(document.documentElement).getPropertyValue('--colors-softblack'),
    '--colors-softgrey': getComputedStyle(document.documentElement).getPropertyValue('--colors-softgrey'),
}

const button = document.getElementById('color-scheme')

/**
 * Return true if the color scheme is light, false otherwise
 * @returns {boolean}
 */
function isDarkMode() {
    return document.documentElement.style.getPropertyValue('color-scheme') === colorScheme.DARK
}

/**
 * Change the color scheme
 * @param setDarkMode {boolean}
 */
function changeColorScheme(setDarkMode) {
    if (setDarkMode && !isDarkMode()) {
        button.classList.add('dark')
        document.documentElement.style.setProperty('color-scheme', colorScheme.DARK)
        Object.entries(colors).forEach(([prop, value], i, colors) => document.documentElement.style.setProperty(colors.at(i - 1)[0], value))
    } else if (isDarkMode()) {
        button.classList.remove('dark')
        document.documentElement.style.setProperty('color-scheme', colorScheme.LIGHT)
        Object.entries(colors).forEach(([prop, value], i, colors) => document.documentElement.style.setProperty(prop, value))
    }
}

/**
 * Handle the click on the color scheme button
 */
function handleClick() {
    changeColorScheme(!isDarkMode())
}
