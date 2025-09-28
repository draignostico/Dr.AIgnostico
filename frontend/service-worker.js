const urlsToCache = [
  //CSS
  '/css/cadastro.css',
  '/css/diagnostico.css',
  '/css/editardados.css',
  '/css/estilo.css',
  '/css/informacoes.css',
  '/css/login.css',
  '/css/navbar.css',
  '/css/perfil.css',
  '/css/pesquisa.css',
  '/css/recuperarsenha.css',
  '/css/suporte.css',

   //JS
  '/js/load-navbar.js',

  //PHP - HTML
  '/Cadastro.php',
  '/Conexao.php',
  '/Diagnostico.php',
  '/Home.php',
  '/Index.php',
  '/Informacoes.php',
  '/Login.php',
  '/Navbar.php',
  '/Perfil.php',
  '/Pesquisa.php',
  '/Recuperarsenha.php',
  '/Salvar_historico.php',
  '/Suporte.php',

  //IMG
  '/img/configuracao.png',
  '/img/doutor.png',
  '/img/iconbaixar.png',
  '/img/icondocs.png',
  '/img/perfil.png',

];

// Instalação do Service Worker
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        return cache.addAll(urlsToCache);
      })
  );
});

// Interceptar solicitações
self.addEventListener('fetch', event => {
  event.respondWith(
    caches.match(event.request)
      .then(response => {
        return response || fetch(event.request);
      })
  );
});

// Limpar caches antigos
self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cache => {
          if (cache !== CACHE_NAME) {
            return caches.delete(cache);
          }
        })
      );
    })
  );
});