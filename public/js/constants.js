const BASE_PATH = '/kartalit'
const PATHS = {
    icons: {
        mapa: `${BASE_PATH}/img/icons/mapa`
    }
}

const usuariId = document.head.querySelector('meta[name="usuariId"]')?.getAttribute("value") ?? null;
const llibreId = document.head.querySelector('meta[name="llibre"]')?.getAttribute("value") ?? null;
const obres = document.head.querySelector('meta[name="obres"]')?.getAttribute("value").split(",") ?? [];