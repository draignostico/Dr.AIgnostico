// load-navbar.js
document.addEventListener('DOMContentLoaded', function() {
    // Verifica se estamos em uma página que deve ter o navbar
    const noNavbarPages = ['index.html', 'login.html', 'cadastro.html', 'recuperarsenha.html'];
    const currentPage = window.location.pathname.split('/').pop();
    
    if (noNavbarPages.includes(currentPage)) {
        return; // Não carrega o navbar nas páginas de autenticação
    }
    
    // Carrega o navbar
    fetch('Navbar.php')
        .then(response => response.text())
        .then(data => {
            document.body.insertAdjacentHTML('afterbegin', data);
            
            // Ajusta o layout para incluir o navbar
            const mainContent = document.querySelector('.main-content');
            if (mainContent) {
                document.body.style.display = 'flex';
                document.body.style.minHeight = '100vh';
                document.body.style.padding = '20px';
                document.body.style.background = 'radial-gradient(circle, #0066e6, #003b8f)';
                
                // Destaca o item ativo no menu
                const menuItems = document.querySelectorAll('.menu a');
                
                menuItems.forEach(item => {
                    item.classList.remove('active');
                    if (item.getAttribute('href') === currentPage) {
                        item.classList.add('active');
                    }
                });
            }
        })
        .catch(error => {
            console.error('Erro ao carregar o navbar:', error);
        });
});