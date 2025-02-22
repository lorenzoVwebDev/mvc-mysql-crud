import GitRepositoryHeader from './GitRepositoryHeader/GitRepositoryHeader.js'
import Header from './Header/Header.js'
import Footer from './Footer/Footer.js'
import Modal from './Modals/response/modal.js'
import EditModal from './Modals/edit/edit_task.modal.js'

document.querySelector('.git-header-section').innerHTML = GitRepositoryHeader;
/* document.querySelector('.main-header').innerHTML = Header; */
document.querySelector('.modal-container').innerHTML = Modal;
document.querySelector('.footer-section').innerHTML = Footer;
document.querySelector('.edit-modal-container').innerHTML = EditModal;
