window.addEventListener('load', function () {
    // Find every COMPONENT tag element. and replace it with a fetched componet from the components directory
    document.querySelectorAll('component').forEach(function (component) {
        const componentName = component.innerText;
        fetch(`components/${componentName}.html`)
            .then(response => response.text())
            .then(html => {
                component.outerHTML = html;
            });
    });

});

function toggleNavbar() {
    const navbar = document.querySelector('nav');
    navbar.classList.toggle('open');
}