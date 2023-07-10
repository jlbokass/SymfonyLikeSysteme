import axios from "axios";

let jsLikeLinks = document.querySelectorAll('a.js-like-link');

jsLikeLinks.forEach((link)=> {
    link.addEventListener('click', onclickBtnLike);
});

function onclickBtnLike(event){
    event.preventDefault();

    const url = this.href;
    const spanCount = this.querySelector('span.js-likes');
    const icone = this.querySelector('i');

    axios.get(url)
        .then((response)=> {
        spanCount.textContent = response.data.likes;
        if (icone.classList.contains('fa-thumbs-up')) icone.classList.replace('fa-thumbs-up', 'fa-thumbs-o-up');
        else icone.classList.replace('fa-thumbs-o-up', 'fa-thumbs-up');
    })
        .catch((error)=> {
            if (error.response.status === 403) window.alert('You must be connected to like');
            else if (error.response.status === 404) window.alert('Error');
        })
    ;
}
