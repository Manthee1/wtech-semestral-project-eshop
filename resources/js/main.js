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


    // Find dropdowns and add event listener to toggle the dropdown
    document.querySelectorAll('.dropdown').forEach(function (dropdown) {
        dropdown.addEventListener('click', function () {
            dropdown.querySelector('.dropdown-menu').classList.toggle('show');
        });
    });
    // Close dropdown when clicked outside
    document.addEventListener('click', function (event) {
        if (!event.target.closest('.dropdown')) {
            document.querySelectorAll('.dropdown-menu').forEach(function (dropdownMenu) {
                dropdownMenu.classList.remove('show');
            });
        }
    });



});


window.formatPrice = function (price) {
    // Euro without decimals and with commas every 3 digits and no decimals
    // Saudi Arabia
    return new Intl.NumberFormat('sk-SK', { style: 'currency', currency: 'EUR', minimumFractionDigits: 0 }).format(price);
}


// Make a confirm modal handler

// It will work much like the window.confirm() function. except you have to await for this ones response
window.confirmModal = async function (message = 'Are you sure?', conf) {
    const confirmModal = document.querySelector('.confirm-modal');
    confirmModal.querySelector('.confirm-modal-body p').innerText = message;
    confirmModal.style.display = '';

    return new Promise((resolve, reject) => {
        confirmModal.querySelector('.confirm-modal-footer .button-outlined').addEventListener('click', function () {
            confirmModal.style.display = 'none';
            resolve(false);
        });

        confirmModal.querySelector('.confirm-modal-footer .button-filled').addEventListener('click', function () {
            confirmModal.style.display = 'none';
            resolve(true);
        });

        confirmModal.querySelector('.close-icon').addEventListener('click', function () {
            confirmModal.style.display = 'none';
            resolve(false);
        });
    });
}



// Add a simple toast message handler. It will show a message for 3 seconds and then hide it
// <div class="toast-message" style="display:none;">
//     <p>Item added to cart</p>
// </div>
// It should also slide the other toasts down if ne disapears under them
window.toast = function (message, status = 'success') {
    const toastsContainer = document.querySelector('#toasts-container');
    const toast = document.createElement('div');
    toast.className = 'toast-message';
    toast.innerHTML = `<span class="close-progress"></span><p>${message}</p>`;
    toastsContainer.appendChild(toast);
    toast.style.display = '';
    toast.classList.add(status);
    toast.querySelector('.close-progress').style.width = '0%';
    setTimeout(function () {
        toast.querySelector('.close-progress').style.width = '100%';
    }, 100);

    function removeToast(toast) {
        toast.remove();
    }
    setTimeout(function () {
        removeToast(toast);
    }, 3100);
    toast.addEventListener('click', function () {
        removeToast(toast);
    });




}


window.setCartCount = function (count) {
    const cartCount = document.querySelector('.cart-count');
    cartCount.innerText = count;
    cartCount.style.display = count > 0 ? '' : 'none';
}
