const main_img = document.querySelector('.main_img')
const thumbnails = document.querySelectorAll('.thumbnail')


thumbnails.forEach(thumb => {
    thumb.addEventListener('click', function(){
        const active = document.querySelector('.actives')
        active.classList.remove('actives')
        thumb.classList.add('actives')
        main_img.src = thumb.src
    })
})