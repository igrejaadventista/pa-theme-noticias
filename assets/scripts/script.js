import '../../node_modules/slim-js/dist/index';
import '../../node_modules/slim-js/dist/directives/all';
import './load-more';

const audiomeLabels = { es: 'escuchar noticia', pt: 'ouvir notícia' };
const audiomeLang = document.documentElement.lang.slice(0, 2);
const audiomeText = audiomeLabels[audiomeLang] ?? 'ouvir notícia';

const observer = new MutationObserver(() => {
    const el = document.getElementById('audiomeouca');
    if (el) {
        el.textContent = audiomeText;
        observer.disconnect();
    }
});
observer.observe(document.body, { childList: true, subtree: true });