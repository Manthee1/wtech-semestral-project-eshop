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

    // Put every placeholder having lement into a div with class name input-group
    document.querySelectorAll('.form-section input, .form-section textarea').forEach(function (input) {
        const className = input.className;
        const placeholder = input.placeholder + (input.required ? '<span>*</span>' : '');

        input.placeholder = '';
        input.className = '';
        input.outerHTML = `<div class="input-wrapper ${className}">
        ${input.outerHTML}
        <label for="${input.id}">${placeholder}</label>
        </div>`;
    });
});

function toggleNavbar() {
    const navbar = document.querySelector('nav');
    navbar.classList.toggle('open');
}