const $closeFlash = document.querySelector('.close-fm-button');
$closeFlash.onclick = closeFlashMessage;

function closeFlashMessage() {
const $flashMessage = document.querySelector('.alert-dismissible');
$flashMessage.style.display = "none";
}