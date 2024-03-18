class MatoGallery {
    constructor(selector) {
        this.gallery = document.querySelector(selector);

        // Get all the img src and put them in an array. Remove everything from the gallery and then add the images back inside gallery-item > content > img
        const images = [];
        this.gallery.querySelectorAll('img').forEach((item) => {
            images.push(item.src);
            item.remove();
        });

        images.forEach((src) => {
            const galleryItem = document.createElement('div');
            galleryItem.classList.add('gallery-item');
            const content = document.createElement('div');
            content.classList.add('content');
            const img = document.createElement('img');
            img.src = src;
            content.appendChild(img);
            galleryItem.appendChild(content);
            this.gallery.appendChild(galleryItem);
        });

        this.gallery.classList.add('mato-gallery');


        this.getVal = (elem, style) => parseInt(window.getComputedStyle(elem).getPropertyValue(style));
        this.getHeight = (item) => item.querySelector('.content').getBoundingClientRect().height;
        this.resizeAll = () => {
            const altura = this.getVal(this.gallery, 'grid-auto-rows');
            const gap = this.getVal(this.gallery, 'grid-row-gap');
            this.gallery.querySelectorAll('.gallery-item').forEach((item) => {
                const el = item;
                el.style.gridRowEnd = `span ${Math.ceil((this.getHeight(item) + gap) / (altura + gap))}`;
            });
        };
        this.gallery.querySelectorAll('img').forEach((item) => {
            const altura = this.getVal(this.gallery, 'grid-auto-rows');
            const gap = this.getVal(this.gallery, 'grid-row-gap');
            const gitem = item.parentElement.parentElement;
            gitem.style.gridRowEnd = `span ${Math.ceil((this.getHeight(gitem) + gap) / (altura + gap))}`;
            item.classList.remove('byebye');
        });



        window.addEventListener('resize', this.resizeAll);
        const galleryItems = this.gallery.querySelectorAll('.gallery-item');
        for (let index = 0; index < galleryItems.length; index++) {
            const item = galleryItems[index];
            item.addEventListener('click', (e) => {
                // item.classList.toggle('full');
                // this.gallery.classList.toggle('full');
                // If the image was clicked and we are full screen, dont do anything
                console.log(e.target);
                if (e.target.tagName === 'IMG' && item.classList.contains('full'))
                    return;

                // If the image was clicked and we are not full screen, make it full
                if (!item.classList.contains('full')) {
                    item.classList.add('full');
                    this.gallery.classList.add('full');
                    this.gallery.setAttribute('data-full-index', index + 1);
                    return;
                }

                // If we are full screen and we clicked outside the image, make it normal
                item.classList.remove('full');
                this.gallery.classList.remove('full');
                this.gallery.removeAttribute('data-full-index');

            });
        };

        // Add back and next buttons
        const back = document.createElement('div');
        back.classList.add('gallery-back');
        back.innerHTML = '<ion-icon name="chevron-back-outline"></ion-icon>';
        const next = document.createElement('div');
        next.classList.add('gallery-next');
        next.innerHTML = '<ion-icon name="chevron-forward-outline"></ion-icon>';
        this.gallery.appendChild(back);
        this.gallery.appendChild(next);

        const maximize = (index) => {
            // Wrap index around
            let galleryItems = this.gallery.querySelectorAll('.gallery-item');
            if (index > galleryItems.length)
                index = 1;
            if (index < 1)
                index = galleryItems.length;

            console.log(galleryItems, index);
            const item = galleryItems[index - 1];
            // Remove full from all items
            this.gallery.querySelectorAll('.full').forEach((item) => {
                item.classList.remove('full');
            });
            this.gallery.setAttribute('data-full-index', index);
            item.classList.add('full');
            item.style.transition = 'none';
            item.querySelector('img').style.transform = 'none';
            item.querySelector('img').style.transition = 'none';
            item.querySelector('img').style.animation = 'none';


        }

        back.addEventListener('click', () => {
            const index = parseInt(this.gallery.getAttribute('data-full-index'));
            maximize(index - 1);
        });

        next.addEventListener('click', () => {
            const index = parseInt(this.gallery.getAttribute('data-full-index'));
            maximize(index + 1);
        });

        this.resizeAll();
    }
}
