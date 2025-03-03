let pagina = document.head.querySelector('meta[name="pagina"]')?.getAttribute("value") || 1;
pagina = parseInt(pagina);
let paginaTotal = document.head.querySelector('meta[name="paginaTotal"]')?.getAttribute("value") || 1;
paginaTotal = parseInt(paginaTotal);

navPagination = document.body.querySelectorAll('nav[id="pagination-biblioteca"]');

function carregarPagina(pagina) {
    window.location.replace(`${BASE_PATH}/biblioteca?pagina=${pagina}`);
}